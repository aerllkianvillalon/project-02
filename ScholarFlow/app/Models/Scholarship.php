<?php
// app/Models/Scholarship.php

require_once ROOT . '/core/Model.php';

class Scholarship extends Model
{
    protected static string $table = 'scholarships';

    public function allActive(): array
    {
        // Auto-close scholarships past their deadline
        $this->execute(
            "UPDATE scholarships SET status = 'closed' WHERE status = 'active' AND deadline < CURDATE()"
        );
        return $this->query(
            "SELECT * FROM scholarships WHERE status = 'active' ORDER BY deadline ASC"
        );
    }

    public function allWithStats(): array
    {
        return $this->query(
            "SELECT s.*, 
                    COUNT(a.id) as application_count,
                    SUM(a.status = 'approved') as approved_count
             FROM scholarships s
             LEFT JOIN applications a ON a.scholarship_id = s.id
             GROUP BY s.id
             ORDER BY s.created_at DESC"
        );
    }

    /**
     * Get scholarships available to a student, factoring in exclusive conflicts.
     */
    public function availableForStudent(int $userId): array
    {
        // If student currently has any exclusive scholarship application
        // in pending/approved state, block applying to any exclusive scholarship
        // (but still allow applications to multiple-allowed scholarships).
        $hasExclusiveBlocking = $this->queryOne(
            "SELECT 1 FROM applications a
             JOIN scholarships s ON s.id = a.scholarship_id
             WHERE a.user_id = ? AND s.allows_multiple = 0 AND a.status IN ('pending','approved')
             LIMIT 1",
            [$userId]
        );

        // If student currently has any multiple-allowed scholarship application
        // in pending/approved state, block applying to any exclusive scholarship.
        // (They may still apply to other multiple-allowed scholarships.)
        $hasMultipleBlocking = $this->queryOne(
            "SELECT 1 FROM applications a
             JOIN scholarships s ON s.id = a.scholarship_id
             WHERE a.user_id = ? AND s.allows_multiple = 1 AND a.status IN ('pending','approved')
             LIMIT 1",
            [$userId]
        );

        // IDs already applied to
        $appliedIds = $this->query(
            "SELECT scholarship_id FROM applications WHERE user_id = ?",
            [$userId]
        );
        $appliedIdList = array_column($appliedIds, 'scholarship_id');

        $scholarships = $this->allActive();

        foreach ($scholarships as &$s) {
            $s['already_applied'] = in_array($s['id'], $appliedIdList);
        // Locked behavior:
        // - If student has an EXCLUSIVE pending/approved => lock ALL scholarships.
        // - Else if student has a MULTIPLE pending/approved => lock ONLY EXCLUSIVE scholarships.
        $s['locked'] = ($hasExclusiveBlocking) || ($hasMultipleBlocking && !$s['allows_multiple']);
        }
        return $scholarships;
    }

    public function isAvailableForStudent(int $scholarshipId, int $userId): array
    {
        $scholarship = $this->find($scholarshipId);
        if (!$scholarship) return ['available' => false, 'reason' => 'Scholarship not found.'];
        if (strtotime($scholarship['deadline']) < strtotime('today')) {
            $this->execute(
                "UPDATE scholarships SET status = 'closed' WHERE id = ?",
                [$scholarshipId]
            );
            return ['available' => false, 'reason' => 'This scholarship deadline has passed.'];
        }
        if ($scholarship['status'] !== 'active') return ['available' => false, 'reason' => 'This scholarship is no longer active.'];

        // Already applied?
        $existing = $this->queryOne(
            "SELECT id FROM applications WHERE user_id = ? AND scholarship_id = ?",
            [$userId, $scholarshipId]
        );
        if ($existing) return ['available' => false, 'reason' => 'You have already applied to this scholarship.'];

        // 1) If the student has an EXCLUSIVE (allows_multiple=0) application
        //    in pending/approved state, lock ALL scholarship applications.
        $hasExclusivePendingOrApproved = $this->queryOne(
            "SELECT 1 FROM applications a
             JOIN scholarships s ON s.id = a.scholarship_id
             WHERE a.user_id = ? AND s.allows_multiple = 0 AND a.status IN ('pending','approved')
             LIMIT 1",
            [$userId]
        );

        if ($hasExclusivePendingOrApproved) {
            return [
                'available' => false,
                'reason'    => 'You already have an exclusive scholarship application pending/approved. You cannot apply to other scholarships yet.'
            ];
        }

        // 2) If the student has one or more MULTIPLE-allowed applications
        //    in pending/approved state:
        //    - lock all EXCLUSIVE scholarships
        //    - keep MULTIPLE-allowed scholarships available
        $hasMultiplePendingOrApproved = $this->queryOne(
            "SELECT 1 FROM applications a
             JOIN scholarships s ON s.id = a.scholarship_id
             WHERE a.user_id = ? AND s.allows_multiple = 1 AND a.status IN ('pending','approved')
             LIMIT 1",
            [$userId]
        );

        if ($hasMultiplePendingOrApproved && !$scholarship['allows_multiple']) {
            return [
                'available' => false,
                'reason'    => 'You already have an allow-multiple scholarship application pending/approved. You cannot apply to exclusive scholarships yet.'
            ];
        }
        return ['available' => true, 'scholarship' => $scholarship];
    }

    public function closeExpired(): void
    {
        $this->execute(
            "UPDATE scholarships SET status = 'closed' WHERE status = 'active' AND deadline < CURDATE()"
        );
    }
}
<?php
// app/Models/Scholarship.php

require_once ROOT . '/core/Model.php';

class Scholarship extends Model
{
    protected static string $table = 'scholarships';

    public function allActive(): array
    {
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
        // Check if student has an approved application for a non-multiple scholarship
        $exclusiveApproved = $this->queryOne(
            "SELECT a.scholarship_id FROM applications a
             JOIN scholarships s ON s.id = a.scholarship_id
             WHERE a.user_id = ? AND s.allows_multiple = 0 AND a.status = 'approved'
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
            // Locked if: student approved for exclusive AND this one doesn't allow multiple
            $s['locked'] = $exclusiveApproved && !$s['allows_multiple'];
        }

        return $scholarships;
    }

    public function isAvailableForStudent(int $scholarshipId, int $userId): array
    {
        $scholarship = $this->find($scholarshipId);
        if (!$scholarship) return ['available' => false, 'reason' => 'Scholarship not found.'];
        if ($scholarship['status'] !== 'active') return ['available' => false, 'reason' => 'This scholarship is no longer active.'];

        // Already applied?
        $existing = $this->queryOne(
            "SELECT id FROM applications WHERE user_id = ? AND scholarship_id = ?",
            [$userId, $scholarshipId]
        );
        if ($existing) return ['available' => false, 'reason' => 'You have already applied to this scholarship.'];

        // If this scholarship does NOT allow multiple:
        // check if student already has approved app in any non-multiple scholarship
        if (!$scholarship['allows_multiple']) {
            $conflict = $this->queryOne(
                "SELECT a.id FROM applications a
                 JOIN scholarships s ON s.id = a.scholarship_id
                 WHERE a.user_id = ? AND s.allows_multiple = 0 AND a.status = 'approved'",
                [$userId]
            );
            if ($conflict) {
                return [
                    'available' => false,
                    'reason'    => 'You already have an approved exclusive scholarship. You cannot apply to another exclusive scholarship.'
                ];
            }
        }

        return ['available' => true, 'scholarship' => $scholarship];
    }
}

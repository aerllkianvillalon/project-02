<?php
// app/Models/Application.php

require_once ROOT . '/core/Model.php';

class Application extends Model
{
    protected static string $table = 'applications';

    public function forStudent(int $userId): array
    {
        return $this->query(
            "SELECT a.*, s.name as scholarship_name, s.amount, s.allows_multiple,
                    u.name as reviewer_name
             FROM applications a
             JOIN scholarships s ON s.id = a.scholarship_id
             LEFT JOIN users u ON u.id = a.reviewed_by
             WHERE a.user_id = ?
             ORDER BY a.created_at DESC",
            [$userId]
        );
    }

    public function findWithDetails(int $id): ?array
    {
        return $this->queryOne(
            "SELECT a.*,
                    s.name as scholarship_name, s.description as scholarship_description,
                    s.amount, s.allows_multiple, s.requirements,
                    u.name as applicant_name, u.email as applicant_email,
                    u.phone, u.address, u.course, u.school, u.gpa, u.year_level,
                    r.name as reviewer_name
             FROM applications a
             JOIN scholarships s ON s.id = a.scholarship_id
             JOIN users u ON u.id = a.user_id
             LEFT JOIN users r ON r.id = a.reviewed_by
             WHERE a.id = ?",
            [$id]
        );
    }

    public function allPending(): array
    {
        return $this->query(
            "SELECT a.*, s.name as scholarship_name, u.name as applicant_name, u.email as applicant_email
             FROM applications a
             JOIN scholarships s ON s.id = a.scholarship_id
             JOIN users u ON u.id = a.user_id
             WHERE a.status = 'pending'
             ORDER BY a.created_at ASC"
        );
    }

    public function allWithDetails(string $status = ''): array
    {
        $where  = $status ? "WHERE a.status = ?" : '';
        $params = $status ? [$status] : [];
        return $this->query(
            "SELECT a.*, s.name as scholarship_name, u.name as applicant_name, u.email
             FROM applications a
             JOIN scholarships s ON s.id = a.scholarship_id
             JOIN users u ON u.id = a.user_id
             {$where}
             ORDER BY a.created_at DESC",
            $params
        );
    }

    public function decide(int $id, int $reviewerId, string $status, string $notes = ''): bool
    {
        return $this->update($id, [
            'status'      => $status,
            'reviewed_by' => $reviewerId,
            'review_notes'=> $notes,
            'reviewed_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function countByStatus(): array
    {
        return $this->query(
            "SELECT status, COUNT(*) as total FROM applications GROUP BY status"
        );
    }
}

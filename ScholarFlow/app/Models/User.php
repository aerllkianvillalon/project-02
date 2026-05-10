<?php
// app/Models/User.php

require_once ROOT . '/core/Model.php';

class User extends Model
{
    protected static string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        return $this->findWhere(['email' => $email]);
    }

    public function createStudent(array $data): int
    {
        return $this->insert([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'password'   => password_hash($data['password'], PASSWORD_BCRYPT),
            'role'       => 'student',
            'phone'      => $data['phone'] ?? null,
            'address'    => $data['address'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public function updateProfile(int $id, array $data): bool
    {
        $allowed = ['name', 'phone', 'address', 'course', 'school', 'gpa', 'year_level', 'avatar'];
        $filtered = array_intersect_key($data, array_flip($allowed));
        $filtered['updated_at'] = date('Y-m-d H:i:s');
        return $this->update($id, $filtered);
    }

    public function allWithRole(string $role = ''): array
    {
        if ($role) {
            return $this->query(
                'SELECT * FROM users WHERE role = ? ORDER BY created_at DESC',
                [$role]
            );
        }
        return $this->query('SELECT * FROM users ORDER BY created_at DESC');
    }

    public function searchWithRole(string $role = '', string $query = ''): array
    {
        $query = trim($query);

        $where = [];
        $params = [];

        if ($role) {
            $where[] = 'role = ?';
            $params[] = $role;
        }

        if ($query !== '') {
            $where[] = '(name LIKE ? OR email LIKE ?)';
            $like = '%' . $query . '%';
            $params[] = $like;
            $params[] = $like;
        }

        $sql = 'SELECT * FROM users';
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY created_at DESC';

        return $this->query($sql, $params);
    }

    public function countByRole(): array
    {
        return $this->query(
            'SELECT role, COUNT(*) as total FROM users GROUP BY role'
        );
    }
}

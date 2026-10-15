<?php
/**
 * Stonk Games Store - User Model
 * Xu ly truy van tai khoan nguoi dung va quan tri vien
 */

class User extends Model {

    public function getAll(bool $includeDeleted = false): array {
        if ($includeDeleted) {
            return $this->fetchAll("SELECT id, username, email, role, created_at, isdelete FROM users ORDER BY id DESC");
        }
        return $this->fetchAll("SELECT id, username, email, role, created_at, isdelete FROM users WHERE isdelete = 0 ORDER BY id DESC");
    }

    public function findById(int $id, bool $includeDeleted = false): ?array {
        if ($includeDeleted) {
            return $this->fetch("SELECT * FROM users WHERE id = ?", [$id]);
        }
        return $this->fetch("SELECT * FROM users WHERE id = ? AND isdelete = 0", [$id]);
    }

    public function findByUsername(string $username): ?array {
        return $this->fetch("SELECT * FROM users WHERE username = ? AND isdelete = 0", [$username]);
    }

    public function findByEmail(string $email): ?array {
        return $this->fetch("SELECT * FROM users WHERE email = ? AND isdelete = 0", [$email]);
    }

    public function create(array $data): int {
        $hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, email, password, role, isdelete) VALUES (?, ?, ?, ?, 0)";
        $this->query($sql, [
            $data['username'],
            $data['email'],
            $hash,
            $data['role'] ?? 'user'
        ]);
        return (int)$this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        if (!empty($data['password'])) {
            $hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $sql = "UPDATE users SET username = ?, email = ?, password = ?, role = ? WHERE id = ?";
            return $this->execute($sql, [$data['username'], $data['email'], $hash, $data['role'], $id]);
        } else {
            $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
            return $this->execute($sql, [$data['username'], $data['email'], $data['role'], $id]);
        }
    }

    public function updateProfile(int $id, string $email, ?string $newPassword = null): bool {
        if (!empty($newPassword)) {
            $hash = password_hash($newPassword, PASSWORD_BCRYPT);
            $sql = "UPDATE users SET email = ?, password = ? WHERE id = ?";
            return $this->execute($sql, [$email, $hash, $id]);
        } else {
            $sql = "UPDATE users SET email = ? WHERE id = ?";
            return $this->execute($sql, [$email, $id]);
        }
    }

    /**
     * Xoa mem (Soft Delete): Khong xoa ban ghi khoi CSDL ma chi chuyen isdelete sang 1 (true)
     */
    public function delete(int $id): bool {
        $user = $this->findById($id, true);
        if (!$user) {
            return false;
        }
        // Bao ve tai khoan admin mac dinh
        if ($user['username'] === 'admin' || $id === 1) {
            return false;
        }
        return $this->execute("UPDATE users SET isdelete = 1 WHERE id = ?", [$id]);
    }

    public function restore(int $id): bool {
        return $this->execute("UPDATE users SET isdelete = 0 WHERE id = ?", [$id]);
    }

    public function count(): int {
        return (int)$this->fetchColumn("SELECT COUNT(*) FROM users WHERE isdelete = 0");
    }
}

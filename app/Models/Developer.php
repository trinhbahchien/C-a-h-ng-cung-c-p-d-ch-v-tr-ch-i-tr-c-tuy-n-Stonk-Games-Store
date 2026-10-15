<?php
/**
 * Stonk Games Store - Developer Model
 * Quan ly thong tin studio nha phat trien game
 */

class Developer extends Model {

    public function getAll(): array {
        return $this->fetchAll("SELECT * FROM developers ORDER BY name ASC");
    }

    public function findById(int $id): ?array {
        return $this->fetch("SELECT * FROM developers WHERE id = ?", [$id]);
    }

    public function create(array $data): int {
        $sql = "INSERT INTO developers (name, slug, logo, description) VALUES (?, ?, ?, ?)";
        $this->query($sql, [
            $data['name'],
            $data['slug'],
            $data['logo'] ?? null,
            $data['description'] ?? null
        ]);
        return (int)$this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE developers SET name = ?, slug = ?, logo = ?, description = ? WHERE id = ?";
        return $this->execute($sql, [
            $data['name'],
            $data['slug'],
            $data['logo'] ?? null,
            $data['description'] ?? null,
            $id
        ]);
    }

    public function delete(int $id): bool {
        return $this->execute("DELETE FROM developers WHERE id = ?", [$id]);
    }

    public function count(): int {
        return (int)$this->fetchColumn("SELECT COUNT(*) FROM developers");
    }
}

<?php
/**
 * Stonk Games Store - Category Model
 * Quan ly danh muc / the loai tro choi
 */

class Category extends Model {

    public function getAll(): array {
        return $this->fetchAll("SELECT * FROM categories ORDER BY name ASC");
    }

    public function findById(int $id): ?array {
        return $this->fetch("SELECT * FROM categories WHERE id = ?", [$id]);
    }

    public function findBySlug(string $slug): ?array {
        return $this->fetch("SELECT * FROM categories WHERE slug = ?", [$slug]);
    }

    public function create(array $data): int {
        $sql = "INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)";
        $this->query($sql, [
            $data['name'],
            $data['slug'],
            $data['description'] ?? null
        ]);
        return (int)$this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE categories SET name = ?, slug = ?, description = ? WHERE id = ?";
        return $this->execute($sql, [
            $data['name'],
            $data['slug'],
            $data['description'] ?? null,
            $id
        ]);
    }

    public function delete(int $id): bool {
        return $this->execute("DELETE FROM categories WHERE id = ?", [$id]);
    }

    public function count(): int {
        return (int)$this->fetchColumn("SELECT COUNT(*) FROM categories");
    }
}

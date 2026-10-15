<?php
/**
 * Stonk Games Store - SystemRequirement Model
 * Quan ly cau hinh phan cung Toi thieu va De nghi cho tro choi
 */

class SystemRequirement extends Model {

    public function getByGameId(int $gameId): ?array {
        return $this->fetch("SELECT * FROM system_requirements WHERE game_id = ?", [$gameId]);
    }

    public function save(int $gameId, array $data): bool {
        $existing = $this->getByGameId($gameId);

        if ($existing) {
            $sql = "UPDATE system_requirements SET 
                    min_os = ?, min_cpu = ?, min_ram = ?, min_gpu = ?, min_storage = ?,
                    rec_os = ?, rec_cpu = ?, rec_ram = ?, rec_gpu = ?, rec_storage = ?
                    WHERE game_id = ?";
            return $this->execute($sql, [
                $data['min_os'] ?? 'Windows 10 64-bit',
                $data['min_cpu'] ?? 'Intel Core i5 / AMD Ryzen 5',
                $data['min_ram'] ?? '8 GB RAM',
                $data['min_gpu'] ?? 'NVIDIA GTX 1060 / AMD RX 580',
                $data['min_storage'] ?? '50 GB available space',
                $data['rec_os'] ?? 'Windows 10/11 64-bit',
                $data['rec_cpu'] ?? 'Intel Core i7 / AMD Ryzen 7',
                $data['rec_ram'] ?? '16 GB RAM',
                $data['rec_gpu'] ?? 'NVIDIA RTX 3060 / AMD RX 6700 XT',
                $data['rec_storage'] ?? '50 GB SSD',
                $gameId
            ]);
        } else {
            $sql = "INSERT INTO system_requirements 
                    (game_id, min_os, min_cpu, min_ram, min_gpu, min_storage, rec_os, rec_cpu, rec_ram, rec_gpu, rec_storage)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            return $this->execute($sql, [
                $gameId,
                $data['min_os'] ?? 'Windows 10 64-bit',
                $data['min_cpu'] ?? 'Intel Core i5 / AMD Ryzen 5',
                $data['min_ram'] ?? '8 GB RAM',
                $data['min_gpu'] ?? 'NVIDIA GTX 1060 / AMD RX 580',
                $data['min_storage'] ?? '50 GB available space',
                $data['rec_os'] ?? 'Windows 10/11 64-bit',
                $data['rec_cpu'] ?? 'Intel Core i7 / AMD Ryzen 7',
                $data['rec_ram'] ?? '16 GB RAM',
                $data['rec_gpu'] ?? 'NVIDIA RTX 3060 / AMD RX 6700 XT',
                $data['rec_storage'] ?? '50 GB SSD'
            ]);
        }
    }

    public function delete(int $gameId): bool {
        return $this->execute("DELETE FROM system_requirements WHERE game_id = ?", [$gameId]);
    }
}

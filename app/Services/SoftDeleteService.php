<?php

declare(strict_types=1);

namespace App\Services;

use Core\Database;

final class SoftDeleteService
{
    public function softDelete(string $table, int $id, int $actorId): void
    {
        $sql = sprintf('UPDATE %s SET deleted_at = NOW(), deleted_by = :actor_id WHERE id = :id', $table);
        $stmt = Database::writeConnection()->prepare($sql);
        $stmt->execute(['actor_id' => $actorId, 'id' => $id]);
    }

    public function restore(string $table, int $id): void
    {
        $sql = sprintf('UPDATE %s SET deleted_at = NULL, deleted_by = NULL WHERE id = :id', $table);
        $stmt = Database::writeConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
    }
}

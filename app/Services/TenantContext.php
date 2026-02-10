<?php

declare(strict_types=1);

namespace App\Services;

use Core\Auth;
use Core\Database;

final class TenantContext
{
    public function tenantId(): ?int
    {
        $userId = Auth::id();
        if (!$userId) {
            return null;
        }
        $stmt = Database::connection()->prepare('SELECT tenant_id FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $userId]);
        $tenantId = $stmt->fetchColumn();
        return $tenantId ? (int) $tenantId : null;
    }

    public function enforceTenantFilter(string $sql, string $alias = ''): string
    {
        $column = $alias !== '' ? $alias . '.tenant_id' : 'tenant_id';
        return $sql . ' AND ' . $column . ' = :tenant_id';
    }
}

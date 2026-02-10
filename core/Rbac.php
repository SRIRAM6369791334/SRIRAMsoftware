<?php

declare(strict_types=1);

namespace Core;

final class Rbac
{
    public static function can(int $userId, string $permissionKey): bool
    {
        $sql = 'SELECT 1 FROM user_roles ur
                JOIN role_permissions rp ON rp.role_id = ur.role_id
                JOIN permissions p ON p.id = rp.permission_id
                WHERE ur.user_id = :user_id AND p.permission_key = :permission_key LIMIT 1';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'permission_key' => $permissionKey]);
        return (bool) $stmt->fetchColumn();
    }
}

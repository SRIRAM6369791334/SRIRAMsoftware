<?php

declare(strict_types=1);

namespace App\Services;

use Core\Database;

final class ApprovalChainService
{
    public function createRequest(string $moduleName, int $entityId, int $requestedBy): int
    {
        $stmt = Database::writeConnection()->prepare('INSERT INTO approval_requests (module_name, entity_id, requested_by, status, current_step, created_at) VALUES (:module_name, :entity_id, :requested_by, :status, 1, NOW())');
        $stmt->execute([
            'module_name' => $moduleName,
            'entity_id' => $entityId,
            'requested_by' => $requestedBy,
            'status' => 'pending',
        ]);
        return (int) Database::writeConnection()->lastInsertId();
    }

    public function approveStep(int $requestId, int $approverId, string $note = ''): void
    {
        $db = Database::writeConnection();
        $db->prepare('INSERT INTO approval_actions (request_id, approver_id, action_type, note, created_at) VALUES (:request_id, :approver_id, :action_type, :note, NOW())')
            ->execute(['request_id' => $requestId, 'approver_id' => $approverId, 'action_type' => 'approved', 'note' => $note]);
        $db->prepare('UPDATE approval_requests SET current_step = current_step + 1 WHERE id = :id')
            ->execute(['id' => $requestId]);
    }
}

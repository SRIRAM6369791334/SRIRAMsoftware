<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

final class WorkflowRule extends Model
{
    public function activeByEvent(string $event): array
    {
        $stmt = $this->db->prepare('SELECT * FROM workflow_rules WHERE trigger_event=:event AND is_active=1 ORDER BY priority ASC');
        $stmt->execute(['event' => $event]);
        return $stmt->fetchAll();
    }
}

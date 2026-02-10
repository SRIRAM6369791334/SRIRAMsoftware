<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

final class ApprovalRequest extends Model
{
    public function pending(): array
    {
        return $this->db->query("SELECT * FROM approval_requests WHERE status='pending' ORDER BY id DESC")->fetchAll();
    }
}

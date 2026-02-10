<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

final class Deal extends Model
{
    public function pipeline(): array
    {
        return $this->db->query('SELECT stage, COUNT(*) total, SUM(value_amount) value_total FROM deals GROUP BY stage')->fetchAll();
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use Core\Database;

final class DuplicateDetectionService
{
    public function detectDuplicateLeads(): array
    {
        $sql = 'SELECT email, COUNT(*) total FROM leads WHERE email IS NOT NULL GROUP BY email HAVING COUNT(*) > 1';
        return Database::readConnection()->query($sql)->fetchAll();
    }

    public function detectDuplicateClients(): array
    {
        $sql = 'SELECT company_name, COUNT(*) total FROM clients GROUP BY company_name HAVING COUNT(*) > 1';
        return Database::readConnection()->query($sql)->fetchAll();
    }
}

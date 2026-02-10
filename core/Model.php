<?php

declare(strict_types=1);

namespace Core;

use PDO;

abstract class Model
{
    protected PDO $db;
    protected PDO $dbWrite;

    public function __construct()
    {
        $this->db = Database::readConnection();
        $this->dbWrite = Database::writeConnection();
    }
}

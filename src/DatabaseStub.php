<?php

namespace Shincoder\Harmless;

use Illuminate\Database\Connection;
use Shincoder\Harmless\Factory;

class DatabaseStub
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function drop()
    {
        $platform = $this->getDatabasePlatform();

        $sql = $platform->getDropDatabaseSQL($this->getDatabaseName());

        $this->connection->statement($sql);

        return $this;
    }

    public function getDatabaseName()
    {
        return $this->connection->getDatabaseName();
    }

    protected function getDatabasePlatform()
    {
        return $this->connection->getDoctrineConnection()->getDatabasePlatform();
    }
}

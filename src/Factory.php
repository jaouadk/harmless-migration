<?php

namespace Shincoder\Harmless;

use Illuminate\Config\Repository;
use Illuminate\Database\DatabaseManager;
use Shincoder\Harmless\DatabaseStub;

class Factory
{
    protected $manager;

    protected $config;

    public function __construct(DatabaseManager $manager, Repository $config)
    {
        $this->manager = $manager;
        $this->config = $config;
    }

    public function makeStub($connName)
    {
        $connection = $this->manager->connection($connName);

        $newDatabase = $this->createDatabase($connection);

        $this->switchConfig($connection, $newDatabase);

        $newConnection = $this->refreshConnection($connection);

        return new DatabaseStub($newConnection);
    }

    protected function createDatabase($connection)
    {
        $name = $this->getDatabaseStubName($connection);

        $platform = $this->getDatabasePlatform($connection);

        $sql = $platform->getCreateDatabaseSQL($name);

        $connection->statement($sql);

        return $name;
    }

    protected function switchConfig($connection, $newDatabase)
    {
        $key = 'database.connections.'.$connection->getName().'.database';

        $this->config->set($key, $newDatabase);

        return $this;
    }

    protected function refreshConnection($connection)
    {
        $this->manager->purge($connection->getName());

        return $this->manager->connection($connection->getName());
    }

    protected function getDatabaseStubName($connection)
    {
        return $connection->getDatabaseName().'_stub_'.str_random(10);
    }

    public function getDatabasePlatform($connection)
    {
        return $connection->getDoctrineConnection()->getDatabasePlatform();
    }
}

<?php

namespace Larapack\DoctrineSupport\Drivers;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\AbstractMySQLDriver;
use Doctrine\DBAL\Driver\PDO\MySQL\Driver as PDO_MySQL_Driver;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Larapack\DoctrineSupport\Managers\MySqlSchemaManager;
use Illuminate\Support\Facades\Config;

class MySqlDriver extends AbstractMySQLDriver
{
    /**
     * {@inheritdoc}
     */
    public function connect(array $params, $username = 'root', $password = 'test1234', array $driverOptions = []): DriverConnection
    {
        $username = $username ?? Config::get('database.connections.mysql.username');
        $password = $password ?? Config::get('database.connections.mysql.password');
        
        $params = [
            'user' => $username,
            'password' => $password,
            'driverOptions' => $driverOptions,
            'dbname' => Config::get('database.connections.mysql.database'),
            'host' => Config::get('database.connections.mysql.write.host'),
            'port' => Config::get('database.connections.mysql.write.port'),
        ];

        $pdoDriver = new PDO_MySQL_Driver();
        return $pdoDriver->connect($params);
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(Connection $conn, AbstractPlatform $platform)
    {
        return new MySqlSchemaManager($conn, $platform);
    }

    /**
     * Returns the name of the driver.
     */
    public function getName(): string
    {
        return 'mysql';
    }
}

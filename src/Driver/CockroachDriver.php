<?php

namespace LapayGroup\DoctrineCockroach\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOPgSql;
use Doctrine\DBAL\Driver\PDO;


/**
 * Driver that connects through pdo_pgsql.
 *
 * @deprecated Use {@link PDO\PgSQL\Driver} instead.
 */
class CockroachDriver extends PDOPgSql\Driver
{

    /**
     * {@inheritdoc}
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = [])
    {
        /** @var PDO\Connection $pdo */
        $pdo = call_user_func_array(array($this, 'parent::connect'), func_get_args());

//        $res = $pdo->exec('set experimental_serial_normalization=sql_sequence;');

        return $pdo;
    }

    public function getSchemaManager(Connection $conn)
    {
        return new CockroachSchemaManager($conn);
    }

}
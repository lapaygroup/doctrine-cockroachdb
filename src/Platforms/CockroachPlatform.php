<?php

namespace LapayGroup\DoctrineCockroach\Platforms;

use Doctrine\DBAL\Platforms\PostgreSQL100Platform;

class CockroachPlatform extends PostgreSQL100Platform
{
    /**
     * {@inheritDoc}
     */
    public function getListNamespacesSQL(): string
    {
        return "SELECT schema_name AS nspname
                FROM   information_schema.schemata
                WHERE  schema_name NOT LIKE 'pg\_%'
                AND    schema_name != 'information_schema'
                AND    schema_name != 'crdb_internal'";
    }

    /**
     * {@inheritDoc}
     */
    public function getListSequencesSQL($database): string
    {
        return "SELECT sequence_name AS relname,
                       sequence_schema AS schemaname
                FROM   information_schema.sequences
                WHERE  sequence_schema NOT LIKE 'pg\_%'
                AND    sequence_schema != 'information_schema'
                AND    sequence_schema != 'crdb_internal'";
    }

    /**
     * {@inheritDoc}
     */
    public function getListTablesSQL(): string
    {
        return "SELECT quote_ident(table_name) AS table_name,
                       table_schema AS schema_name
                FROM   information_schema.tables
                WHERE  table_schema NOT LIKE 'pg\_%'
                AND    table_schema != 'information_schema'
                AND    table_name != 'geometry_columns'
                AND    table_name != 'spatial_ref_sys'
                AND    table_type != 'VIEW'
                AND    table_schema != 'crdb_internal'";
    }

    /**
     * {@inheritDoc}
     */
    protected function initializeDoctrineTypeMappings()
    {
        parent::initializeDoctrineTypeMappings();

        $this->doctrineTypeMapping = array_merge($this->doctrineTypeMapping, [
            '_text' => 'string',
            '_int8' => 'integer',
            'int8' => 'integer',
            'int2vector' => 'array',
        ]);
    }
}
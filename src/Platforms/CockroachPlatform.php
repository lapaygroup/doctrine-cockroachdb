<?php

namespace LapayGroup\DoctrineCockroach\Platforms;

use Doctrine\DBAL\Platforms\PostgreSQL100Platform;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;

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
            'int2vector' => 'array',
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getIntegerTypeDeclarationSQL(array $column)
    {
        if (! empty($column['autoincrement'])) {
            return 'SERIAL';
        }

        return 'INT4';
    }

    /**
     * {@inheritDoc}
     */
    public function getAdvancedForeignKeyOptionsSQL(ForeignKeyConstraint $foreignKey)
    {
        $query = '';

        if ($foreignKey->hasOption('match')) {
            $query .= ' MATCH ' . $foreignKey->getOption('match');
        }

        $query .= parent::getAdvancedForeignKeyOptionsSQL($foreignKey);

        // Waiting for resolved https://github.com/cockroachdb/cockroach/issues/31632
        /*if ($foreignKey->hasOption('deferrable') && $foreignKey->getOption('deferrable') !== false) {
            $query .= ' DEFERRABLE';
        } else {
            $query .= ' NOT DEFERRABLE';
        }

        if (
            ($foreignKey->hasOption('feferred') && $foreignKey->getOption('feferred') !== false)
            || ($foreignKey->hasOption('deferred') && $foreignKey->getOption('deferred') !== false)
        ) {
            $query .= ' INITIALLY DEFERRED';
        } else {
            $query .= ' INITIALLY IMMEDIATE';
        }*/

        return $query;
    }
}
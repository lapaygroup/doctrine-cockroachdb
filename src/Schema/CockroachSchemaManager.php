<?php

namespace LapayGroup\DoctrineCockroach\Schema;

use Doctrine\DBAL\Schema\PostgreSqlSchemaManager;
use Doctrine\DBAL\Schema\Sequence;

class CockroachSchemaManager extends PostgreSqlSchemaManager
{
    /**
     * {@inheritdoc}
     */
    protected function _getPortableSequenceDefinition($sequence)
    {
        if ($sequence['schemaname'] !== 'public') {
            $sequenceName = $sequence['schemaname'] . '.' . $sequence['relname'];
        } else {
            $sequenceName = $sequence['relname'];
        }

        if (! isset($sequence['increment_by'], $sequence['min_value'])) {
            $sequence['min_value'] = 0;
            $sequence['increment_by'] = 0;
//            /** @var string[] $data */
//            $data = $this->_conn->fetchAssoc(
//                'SELECT min_value, increment_by FROM ' . $this->_platform->quoteIdentifier($sequenceName)
//            );
//
//            $sequence += $data;
        }

        return new Sequence($sequenceName, (int) $sequence['increment_by'], (int) $sequence['min_value']);
    }
}
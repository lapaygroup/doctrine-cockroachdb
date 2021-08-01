<?php

namespace LapayGroup\DoctrineCockroach\Schema;

use Doctrine\DBAL\Schema\PostgreSqlSchemaManager;
use Doctrine\DBAL\Schema\Sequence;

class CockroachSchemaManager extends PostgreSqlSchemaManager
{
    protected function _getPortableSequenceDefinition($sequence): Sequence
    {
        if ($sequence['schemaname'] !== 'public') {
            $sequenceName = $sequence['schemaname'] . '.' . $sequence['relname'];
        } else {
            $sequenceName = $sequence['relname'];
        }

        if (!isset($sequence['increment_by'], $sequence['min_value'])) {
            $sequence['min_value'] = 0;
            $sequence['increment_by'] = 0;

            /** @var string[] $data */
            $data = $this->_conn->fetchAssociative('SHOW CREATE ' . $this->_platform->quoteIdentifier($sequenceName));
            if (!empty($data['create_statement'])) {
                $matches = [];
                preg_match_all('/ -?\d+/', $data['create_statement'],  $matches);
                if (!empty($matches[0])) {
                    $matches = array_map('trim', $matches[0]);
                    $sequence['min_value'] = $matches[0];
                    $sequence['increment_by'] = $matches[2];
                }
            }
        }

        return new Sequence($sequenceName, (int) $sequence['increment_by'], $sequence['min_value']);
    }
}

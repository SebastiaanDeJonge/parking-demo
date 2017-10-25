<?php
namespace SebastiaanDeJonge\Pm\DataType;

use SebastiaanDeJonge\Pm\Abstracts\AbstractSingleton;
use SebastiaanDeJonge\Pm\Database\Database;
use SebastiaanDeJonge\Pm\Database\Query;
use SebastiaanDeJonge\Pm\Exceptions\ClassNotFoundException;
use SebastiaanDeJonge\Pm\Utility\LogUtility;

/**
 * Abstract data type
 *
 * @package SebastiaanDeJonge\Pm\DataType
 */
abstract class AbstractDataType extends AbstractSingleton
{
    /**
     * @var string
     */
    protected $labelProperty;

    /**
     * @var string
     */
    protected $tableName = '';

    /**
     * @var int
     */
    protected $limit = 100;

    /**
     * @var array
     */
    protected $defaultProperties = [];

    /**
     * @var array
     */
    protected $childMappingConfiguration = [];

    /**
     * Creates a standard SELECT query
     */
    protected function createQuery()
    {
        $query = new Query();
        $query->setType(Query::TYPE_SELECT)
            ->setSelectFields(!empty($this->defaultProperties) ? $this->defaultProperties : ['*'])
            ->setTableName($this->tableName);
        $this->insertJoins($query);
        return $query;
    }

    /**
     * @param Query $query
     * @throws ClassNotFoundException
     */
    protected function insertJoins(Query $query)
    {
        foreach ($this->childMappingConfiguration as $property => $targetDataType) {
            if (class_exists($targetDataType)) {
                /** @var AbstractDataType $childClass */
                $childClass = $targetDataType::getInstance();
                $query->addJoin(
                    'RIGHT OUTER JOIN ' . $childClass->tableName . ' ON ' . $childClass->tableName . '.id = ' .
                    $query->getTableName() . '.' . $property
                );
                $query->addSelectField($childClass->tableName . '.' . $childClass->labelProperty . ' AS ' . $childClass->tableName);
            } else {
                LogUtility::error('Class ' . $targetDataType . ' was not found');
                throw new ClassNotFoundException(
                    'The class was not found, see the log for more details',
                    1508949641482
                );
            }
        }
    }

    /**
     * @param string $propertyName
     * @param string $propertyValue
     * @return array
     */
    protected function findByProperty(string $propertyName, string $propertyValue): array
    {
        /** @var Database $database */
        $database = Database::getInstance();
        $query = $this->createQuery()
            ->addClause($this->tableName . '.' . $propertyName .' = :' . $propertyName);
        $result = $database->executeSelectQuery($query->build(), [$propertyName => $propertyValue]);
        return $result;
    }
}
<?php
namespace SebastiaanDeJonge\Pm\DataType;

/**
 * Country data type
 *
 * @package SebastiaanDeJonge\Pm\DataType
 */
class Country extends AbstractDataType
{
    /**
     * @var string
     */
    protected $labelProperty = 'name';

    /**
     * @var string
     */
    protected $tableName = 'country';

    /**
     * @param string $name
     * @return array
     */
    public function findByName(string $name): array
    {
        return $this->findByProperty('name', $name);
    }
}
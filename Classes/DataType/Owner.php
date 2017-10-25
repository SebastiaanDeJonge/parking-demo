<?php
namespace SebastiaanDeJonge\Pm\DataType;

/**
 * Owner data type
 *
 * @package SebastiaanDeJonge\Pm\DataType
 */
class Owner extends AbstractDataType
{
    /**
     * @var string
     */
    protected $labelProperty = 'name';

    /**
     * @var string
     */
    protected $tableName = 'owner';

    /**
     * @var string
     */
    protected $entityName = 'owner';
}
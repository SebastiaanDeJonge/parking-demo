<?php
namespace SebastiaanDeJonge\Pm\DataType;

/**
 * Currency data type
 *
 * @package SebastiaanDeJonge\Pm\DataType
 */
class Currency extends AbstractDataType
{
    /**
     * @var string
     */
    protected $labelProperty = 'symbol';

    /**
     * @var string
     */
    protected $tableName = 'currency';
}
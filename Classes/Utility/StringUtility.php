<?php
namespace SebastiaanDeJonge\Pm\Utility;

/**
 * String utility
 *
 * @package SebastiaanDeJonge\Pm\Utility
 */
class StringUtility
{
    /**
     * @param string $start
     * @param string $string
     * @return bool
     */
    public static function startsWith(string $start, string $string): bool
    {
        return (strpos($string, $start) === 0);
    }
}
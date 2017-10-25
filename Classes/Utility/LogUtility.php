<?php
namespace SebastiaanDeJonge\Pm\Utility;

use SebastiaanDeJonge\Pm\Exceptions\FileNotWritableException;

/**
 * Class LogUtility
 * @package SebastiaanDeJonge\Pm
 */
class LogUtility
{
    const LOG_PATH = '/Logs/';

    /**
     * @param string $message
     */
    public static function error(string $message)
    {
        self::write('[ERROR] ' . $message);
    }

    /**
     * @param string $message
     */
    public static function warning(string $message)
    {
        self::write('[WARNING] ' . $message);
    }

    /**
     * @param string $message
     */
    public static function information(string $message)
    {
        self::write('[INFO] ' . $message);
    }

    /**
     * @param string $message
     * @param mixed $data
     * @param bool $allowNull
     */
    public static function debug(string $message, mixed $data = null, $allowNull = false)
    {
        $output = '[DEBUG] ' . $message . "\n";
        if (is_object($data)) {
            if (method_exists($data, 'toString')) {
                $output .= $data->toString();
            } else {
                $class = get_class($data);
                self::error(
                    'Can\'t output object ' . $class . ', it must contain a toString() method in order to output it'
                );
            }
        } elseif($data !== null || $allowNull) {
            $output .= print_r($data, true);
        }
        self::write('[DEBUG] ' . $output);
    }

    /**
     * @param string $message
     */
    public static function notice(string $message)
    {
        self::write('[NOTICE] ' . $message);
    }

    /**
     * @param string $message
     * @throws FileNotWritableException
     */
    private static function write(string $message)
    {
        $filePath = PM_APPLICATION_ROOT . self::LOG_PATH . 'Application.log';
        $result = file_put_contents($filePath, $message . "\n", FILE_APPEND);
        if ($result === false) {
            throw new FileNotWritableException(
                'The application was unable to write to ' . $filePath,
                1508825970142
            );
        }
    }
}
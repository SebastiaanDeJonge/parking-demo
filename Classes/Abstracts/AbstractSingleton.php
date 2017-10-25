<?php
namespace SebastiaanDeJonge\Pm\Abstracts;

use SebastiaanDeJonge\Pm\Exceptions\CantCloneSingletonException;

/**
 * Abstract singleton
 *
 * @package SebastiaanDeJonge\Pm\Abstracts
 */
abstract class AbstractSingleton
{
    /**
     * @var array
     */
    private static $instances = [];

    /**
     * Constructor
     */
    final protected function __construct()
    {
        // Stub
    }

    /**
     * @return mixed
     */
    final public static function getInstance()
    {
        $class = get_called_class();
        if (empty(self::$instances[$class])) {
            self::$instances[$class] = 'init';
            self::$instances[$class] = new $class(func_get_args());
            if (method_exists(self::$instances[$class], 'init')) {
                self::$instances[$class]->init();
            }
        }
        return self::$instances[$class];
    }

    /**
     * Prevent cloning of the child-class
     *
     * @throws CantCloneSingletonException
     */
    final public function __clone()
    {
        throw new CantCloneSingletonException('You are not allowed to clone a singleton class');
    }
}
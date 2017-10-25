<?php
namespace SebastiaanDeJonge\Pm\Utility;

/**
 * Class EnvironmentUtility
 * @package SebastiaanDeJonge\Pm
 */
class EnvironmentUtility
{
    /**
     * @var array
     */
    static private $allowedEnvironments = [
        'Development',
        'Testing',
        'Staging',
        'Production'
    ];

    /**
     * Gets the current environment set from the environment variables, default
     * @return string
     */
    static public function getCurrentEnvironment()
    {
        $currentEnvironment = 'Development';
        $setEnvironment = !empty($_SERVER['PM_ENVIRONMENT']) ? $_SERVER['PM_ENVIRONMENT'] : '';
        if (self::isValidEnvironment($setEnvironment)) {
            $currentEnvironment = $setEnvironment;
        } else {
            LogUtility::warning(
                'The environment has not been set, or has not been set property. The configured value is \'' .
                $setEnvironment . '\'. The default value \'' . $currentEnvironment . '\' will be used instead.'
            );
        }
        return $currentEnvironment;
    }

    /**
     * @param string $environment
     * @return bool
     */
    public static function isValidEnvironment($environment)
    {
        return in_array($environment, self::$allowedEnvironments);
    }
}
<?php
namespace SebastiaanDeJonge\Pm\Configuration;

use SebastiaanDeJonge\Pm\Abstracts\AbstractSingleton;
use SebastiaanDeJonge\Pm\Exceptions\FileNotFoundException;
use SebastiaanDeJonge\Pm\Exceptions\FileNotReadableException;
use SebastiaanDeJonge\Pm\Exceptions\InvalidConfigurationException;
use SebastiaanDeJonge\Pm\Utility\EnvironmentUtility;
use SebastiaanDeJonge\Pm\Utility\LogUtility;
use Symfony\Component\Yaml\Yaml;

/**
 * A simple configuration manager, which handles loading and reading of Yaml configuration files
 *
 * @package SebastiaanDeJonge\Pm\Configuration
 */
class ConfigurationManager extends AbstractSingleton
{
    private $configurationCache = [];

    /**
     * Gets the main configuration for the current environment
     *
     * @return array
     */
    public function getMainConfiguration(): array
    {
        $environment = EnvironmentUtility::getCurrentEnvironment();
        return $this->getConfigurationForByName($environment);
    }

    /**
     * @return array
     */
    public function getRouteConfiguration(): array
    {
        $configuration = $this->getConfigurationForByName('Routes');
        return $configuration;
    }

    /**
     * Gets the main configuration for the specified name
     *
     * @param string $name
     * @return array
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     * @throws InvalidConfigurationException
     */
    private function getConfigurationForByName(string $name): array
    {
        if (empty($this->configurationCache[$name])) {
            $configurationFilePath = PM_APPLICATION_ROOT . '/Configuration/' . $name . '.yaml';
            $configuration = $this->readConfigurationFile($configurationFilePath);
        } else {
            $configuration = $this->configurationCache[$name];
        }

        return $configuration;
    }

    /**
     * @param string $configurationFilePath
     * @return array
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     * @throws InvalidConfigurationException
     */
    private function readConfigurationFile(string $configurationFilePath): array
    {
        if (!file_exists($configurationFilePath)) {
            LogUtility::error('The configuration "' . $configurationFilePath . '" could not be found');
            throw new FileNotFoundException(
                'The configuration was not found, check the log for more information',
                1508941275421
            );
        }
        if (!is_readable($configurationFilePath)) {
            LogUtility::error('The configuration "' . $configurationFilePath . '" could not be read');
            throw new FileNotReadableException(
                'The configuration was not readable, check the log for more information',
                1508941275422
            );
        }

        // Attempt to read the configuration file
        $configuration = Yaml::parseFile($configurationFilePath);
        if (!is_array($configuration) || empty($configuration)) {
            LogUtility::error(
                'The configuration format appears to be invalid. It should be of type array but was of type "' .
                gettype($configuration) . '"'
            );
            throw new InvalidConfigurationException(
                'The configuration appears to be invalid, check the log for more information',
                1508941504638
            );
        }

        return $configuration;
    }
}
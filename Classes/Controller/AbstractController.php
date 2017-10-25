<?php
namespace SebastiaanDeJonge\Pm\Controller;

use Respect\Rest\Routable;
use SebastiaanDeJonge\Pm\Configuration\ConfigurationManager;
use SebastiaanDeJonge\Pm\Exceptions\InvalidConfigurationException;
use SebastiaanDeJonge\Pm\Utility\LogUtility;
use SebastiaanDeJonge\Pm\Utility\StringUtility;

/**
 * Abstract controller
 *
 * @package SebastiaanDeJonge\Pm\Controller
 */
class AbstractController implements Routable
{
    /**
     * @return string
     * @throws InvalidConfigurationException
     */
    protected function getLocalRoutePath(): string
    {
        /** @var ConfigurationManager $configurationManager */
        $configurationManager = ConfigurationManager::getInstance();
        $configuration = $configurationManager->getMainConfiguration();

        if (is_array($configuration['application']) && !empty($configuration['application']['path'])) {
            $requestUrl = $_SERVER['REQUEST_URI'];
            $localRoutePath = $requestUrl;
            if (StringUtility::startsWith($configuration['application']['path'], $requestUrl)) {
                $localRoutePath = substr($requestUrl, strlen($configuration['application']['path']));
            }
        } else {
            LogUtility::error(
                'The path is not configured inside the main configuration, thus internal routing paths can\'t be 
                determined.'
            );
            throw new InvalidConfigurationException(
                'The path is not configured, see the log for more details',
                1508953611817
            );
        }

        return $localRoutePath;
    }
}
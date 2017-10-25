<?php
namespace SebastiaanDeJonge\Pm;

use Respect\Rest\Router;
use SebastiaanDeJonge\Pm\Configuration\ConfigurationManager;
use SebastiaanDeJonge\Pm\Database\Database;
use SebastiaanDeJonge\Pm\Exceptions\InvalidConfigurationException;
use SebastiaanDeJonge\Pm\Exceptions\NoRoutesConfiguredException;
use SebastiaanDeJonge\Pm\Exceptions\RootPathNotDefinedException;
use SebastiaanDeJonge\Pm\Utility\LogUtility;

/**
 * The main application
 *
 * @package SebastiaanDeJonge\Pm
 */
class Application
{
    /**
     * @var array $configuration
     */
    protected $configuration;

    /**
     * @var Router $router
     */
    protected $router;

    /**
     * @var Database
     */
    protected $database;

    /**
     * Runs the application
     *
     * @return void
     * @throws RootPathNotDefinedException
     */
    public function run()
    {
        if (!defined('PM_APPLICATION_ROOT')) {
            LogUtility::error('The root path has not been defined (PM_APPLICATION_ROOT)');
            throw new RootPathNotDefinedException('The root path has not been defined', 1508947390181);
        }

        $this->loadConfiguration();
        $this->initializeDatabase();
        $this->initializeRouter();
        echo $this->router->run();
    }

    /**
     * Loads the environment specific configuration
     *
     * @return void
     */
    private function loadConfiguration()
    {
        /** @var ConfigurationManager $configurationManager */
        $configurationManager = ConfigurationManager::getInstance();
        $this->configuration = $configurationManager->getMainConfiguration();
    }

    /**
     * Initializes the router
     *
     * @return void
     * @throws NoRoutesConfiguredException
     */
    private function initializeRouter()
    {
        /** @var ConfigurationManager $configurationManager */
        $configurationManager = ConfigurationManager::getInstance();
        $routerConfiguration = $configurationManager->getRouteConfiguration();

        if (is_array($routerConfiguration) && !empty($routerConfiguration)) {
            $router = new Router();
            $router->isAutoDispatched = false;
            foreach ($routerConfiguration AS $route => $class) {
                $router->any($route, $class);
            }
            $this->router = $router;
        } else {
            $message = 'No routes were configured';
            LogUtility::error($message);
            throw new NoRoutesConfiguredException($message, 1508946068366);
        }
    }

    /**
     * Initializes the database connection
     *
     * @return void
     * @throws InvalidConfigurationException
     */
    private function initializeDatabase()
    {
        if ($this->hasValidDatabaseConfiguration()) {
            $this->database = Database::getInstance();
            $this->database->connect(
                $this->configuration['database']['host'],
                $this->configuration['database']['port'],
                $this->configuration['database']['databaseName'],
                $this->configuration['database']['username'],
                $this->configuration['database']['password']
            );
        } else {
            $message = 'The database configuration appears to be invalid or incomplete';
            LogUtility::error($message);
            throw new InvalidConfigurationException(
                $message,
                1508947132639
            );
        }
    }

    /**
     * @return bool
     */
    private function hasValidDatabaseConfiguration()
    {
        $properties = ['host', 'port', 'databaseName', 'username', 'password'];
        $hasValidConfiguration = false;
        if (is_array($this->configuration) && !empty($this->configuration['database'])) {
            $hasValidConfiguration = true;
            foreach ($properties as $name) {
                if (empty($this->configuration['database'][$name])) {
                    $hasValidConfiguration = false;
                    break;
                }
            }
        }
        return $hasValidConfiguration;
    }
}
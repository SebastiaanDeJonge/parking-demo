<?php
namespace SebastiaanDeJonge\Pm\Database;

use PDO;
use SebastiaanDeJonge\Pm\Abstracts\AbstractSingleton;
use SebastiaanDeJonge\Pm\Exceptions\DatabaseQueryErrorException;
use SebastiaanDeJonge\Pm\Utility\LogUtility;

/**
 * Extremely simple database handler
 *
 * @package SebastiaanDeJonge\Pm\Database
 */
class Database extends AbstractSingleton
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @param string $hostname
     * @param int $port
     * @param string $databaseName
     * @param string $username
     * @param string $password
     * @return PDO
     */
    public function connect($hostname, $port, $databaseName, $username, $password)
    {
        $dsn = 'mysql:host=' . $hostname . ';port=' . (int)$port . ';dbname=' . $databaseName;
        $this->pdo = new PDO($dsn, $username, $password);
        return $this->pdo;
    }

    /**
     * @param string $query
     * @param array $parameters
     * @return array
     * @throws DatabaseQueryErrorException
     */
    public function executeSelectQuery(string $query, array $parameters = [])
    {
        $statement = $this->pdo->prepare($query);
        $result = $statement->execute($parameters);

        // Check if the query was executed successful
        if ($result === false) {
            LogUtility::error(
                "The following error occurred while executing a database query: \n" . print_r($statement->errorInfo(), true) .
                "\n\nQuery:\n" . print_r($statement->queryString, true)
            );
            throw new DatabaseQueryErrorException(
                'An error occurred while executing a database query. Refer to the error log for more details.',
                1508940404676
            );
        }

        // Return results
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
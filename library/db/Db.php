<?php

namespace library\db;

use library\db\driver\Pdo;
use library\system\Config;

class Db implements DbInterface
{
    /**
     * @var \PDO
     */
    private $connection;

    /**
     * @var \PDOStatement
     */
    private $statement;

    /**
     * Db constructor.
     */
    public function __construct()
    {
        try {
            $config = Config::load('config');
            $this->connection = (new Pdo($config['db']['dsn']))->getInstance();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Prepare statement
     * @param string $sql
     * @return $this
     */
    public function sql(string $sql)
    {
        $this->statement = $this->connection->prepare($sql);
        return $this;
    }

    /**
     * Bind params to statement
     *
     * @param string $parameter
     * @param $variable
     * @param int $data_type
     * @return $this
     */
    public function bind(string $parameter, $variable, $data_type = \PDO::PARAM_STR)
    {
        $this->statement->bindParam($parameter, $variable, $data_type);
        return $this;
    }

    /**
     * Execute SQL query
     * @throws \Exception
     */
    public function execute()
    {
        try {
            $this->executeQuery();
        } catch (\PDOException $e) {
            throw new \Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
        }
    }

    /**
     * @param string $fetchArgument
     * @return array
     * @throws \Exception
     */
    public function all(string $fetchArgument = \PDO::FETCH_ORI_NEXT)
    {
        try {
            if ($this->executeQuery()) {
                return $this->statement->fetchAll(\PDO::FETCH_CLASS, $fetchArgument);
            }
        } catch (\PDOException $e) {
            throw new \Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
        }
    }

    /**
     * @param string $fetchArgument
     * @return object
     * @throws \Exception
     */
    public function one(string $fetchArgument = \PDO::FETCH_ORI_NEXT)
    {
        try {
            if ($this->executeQuery()) {
                return $this->statement->fetchObject($fetchArgument);
            }
        } catch (\PDOException $e) {
            throw new \Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
        }
    }

    /**
     * @param int|null $columnNumber
     * @return string|int|null
     * @throws \Exception
     */
    public function column(int $columnNumber = null)
    {
        try {
            if ($this->executeQuery()) {
                return $this->statement->fetchColumn($columnNumber);
            }
        } catch (\PDOException $e) {
            throw new \Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
        }
    }

    /**
     * Is query can execute
     * @return bool
     */
    private function executeQuery()
    {
        return $this->statement && $this->statement->execute();
    }
}

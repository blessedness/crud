<?php

namespace library\db\driver;

final class Pdo implements DbDriverInterface
{
    /**
     * @var Pdo
     */
    private $pdo;

    /**
     * Pdo constructor.
     * @param string $dsn
     * @param string $username
     * @param string $password
     */
    public function __construct(string $dsn, string $username = null, string $password = null)
    {
        try {
            $this->pdo = (new \PDO($dsn, $username, $password, [\PDO::ATTR_PERSISTENT => false]));
        } catch (\PDOException $e) {
            echo 'Error db connection: ' . $e->getMessage();
        }
    }

    public function getInstance(): \PDO
    {
        return $this->pdo;
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->pdo ? true : false;
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}
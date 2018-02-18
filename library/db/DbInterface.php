<?php

namespace library\db;


interface DbInterface
{
    /**
     * @param string $fetchArgument
     * @return array
     * @throws \Exception
     */
    public function all(string $fetchArgument = \PDO::FETCH_ORI_NEXT);

    /**
     * @param string $fetchArgument
     * @return object
     * @throws \Exception
     */
    public function one(string $fetchArgument = \PDO::FETCH_ORI_NEXT);

    /**
     * @param int|null $columnNumber
     * @return string|int|null
     * @throws \Exception
     */
    public function column(int $columnNumber = null);
}
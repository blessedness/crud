<?php

namespace module\model;

use library\db\Db;
use library\system\Config;

class Vacation
{
    public const STATUS_APPROVED = 1;
    public const STATUS_NOT_APPROVED = 0;

    public const STATUSES = [
        self::STATUS_APPROVED,
        self::STATUS_NOT_APPROVED
    ];

    public $id;
    public $days;
    public $status;
    public $created_by;
    public $update_by;
    public $created_at;
    public $update_at;

    public static function tableName(): string
    {
        return 'vacation';
    }

    /**
     * @param int $userId
     * @return array| Vacation[]
     * @throws \Exception
     */
    public function getAllByUser(int $userId): array
    {
        return (new Db())->sql('SELECT * FROM ' . static::tableName() . ' WHERE created_by = :created_by')
            ->bind(':created_by', $userId, \PDO::PARAM_INT)
            ->all(get_called_class());
    }

    public function getAll()
    {
        return (new Db())->sql('SELECT * FROM ' . static::tableName() . ' as v LEFT JOIN ' . User::tableName() . ' as u ON v.created_by = u.id')
            ->all(get_called_class());
    }

    /**
     * @param int $id
     * @param int $userId
     * @return mixed
     * @throws \Exception
     */
    public function getOneByUser(int $id, int $userId)
    {
        return (new Db())->sql('SELECT * FROM ' . static::tableName() . ' WHERE id = :id AND created_by = :created_by')
            ->bind(':id', $id, \PDO::PARAM_INT)
            ->bind(':created_by', $userId, \PDO::PARAM_INT)
            ->one(get_called_class());
    }

    /**
     * @param int $userId
     * @param int|null $year
     * @return mixed
     * @throws \Exception
     */
    public function getUsedDaysInYear(int $userId, int $year = null)
    {
        $year = $year ?: date('Y');
        $start = mktime(0, 0, 0, 1, 1, $year);
        $end = mktime(0, 0, 0, 12, 31, $year);
        $sql = 'SELECT SUM(days) FROM ' . static::tableName() . ' WHERE created_by = :created_by AND created_at BETWEEN :start AND :end AND status = :status';
        $count = (new Db())->sql($sql)
            ->bind(':created_by', $userId, \PDO::PARAM_INT)
            ->bind(':start', $start, \PDO::PARAM_INT)
            ->bind(':end', $end, \PDO::PARAM_INT)
            ->bind(':status', static::STATUS_APPROVED, \PDO::PARAM_INT)
            ->column();

        return $count ?: 0;
    }

    /**
     * @param int $userId
     * @param int $days
     * @param int $status
     * @throws \Exception
     */
    public function add(int $userId, int $days, int $status)
    {
        $sql = 'INSERT INTO ' . static::tableName() . '(days, status, created_by, updated_by, created_at, updated_at) VALUES(:days, :status, :created_by, :updated_by, :created_at, :updated_at);';
        (new Db())->sql($sql)
            ->bind(':days', $days, \PDO::PARAM_INT)
            ->bind(':status', $status, \PDO::PARAM_INT)
            ->bind(':created_by', $userId, \PDO::PARAM_INT)
            ->bind(':updated_by', $userId, \PDO::PARAM_INT)
            ->bind(':created_at', time(), \PDO::PARAM_INT)
            ->bind(':updated_at', time(), \PDO::PARAM_INT)
            ->execute();
    }

    /**
     * @param int $id
     * @param int $userId
     * @param int $days
     * @param int $status
     * @throws \Exception
     */
    public function update(int $id, int $userId, int $days, int $status)
    {
        $sql = 'UPDATE ' . static::tableName() . ' SET `days` = :days, `status` = :status, `updated_by` = :updated_by, `updated_at` = :updated_at WHERE `id` = :id';

        (new Db())->sql($sql)
            ->bind(':id', $id, \PDO::PARAM_INT)
            ->bind(':status', $status, \PDO::PARAM_INT)
            ->bind(':days', $days, \PDO::PARAM_INT)
            ->bind(':updated_by', $userId, \PDO::PARAM_INT)
            ->bind(':updated_at', time(), \PDO::PARAM_INT)
            ->execute();
    }

    public function getFormattedCreated()
    {
        return date('m/d/Y H:i:s', $this->created_at);
    }

    public function delete()
    {
        $sql = 'DELETE FROM ' . static::tableName() . ' WHERE `id` = :id';
        (new Db())->sql($sql)
            ->bind(':id', $this->id, \PDO::PARAM_INT)
            ->execute();
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getAllDays(): int
    {
        $config = Config::load('config');

        return (int)$config['vacation.days'];
    }

    public function getIsApproved()
    {
        return (int)$this->status === static::STATUS_APPROVED;
    }
}
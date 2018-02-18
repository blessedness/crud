<?php

namespace module\model;

/**
 *
 */
class VacationForm
{
    public $isNew = true;

    public $days = 0;
    public $status = 0;

    private $error = [];

    /**
     * @var User
     */
    private $user;

    /**
     * @var Vacation
     */
    private $model;

    public function __construct(bool $isNew = true)
    {
        $this->isNew = $isNew;
        $this->user = new User();
        $this->model = new Vacation();
    }

    public function validate()
    {
        $this->validateStatus();
        $this->validateDays();

        return empty($this->error) ? true : false;
    }

    protected function validateDays()
    {
        $used = $this->getUsedDays();
        $allDays = $this->model->getAllDays();

        $countDaysValid = $this->isNew ? ($used + $this->days) > $allDays : $this->days > $allDays;

        if (!isset($this->days) || empty($this->days)) {
            $this->error['days'] = 'days field is required';
        } elseif (filter_var($this->days, FILTER_VALIDATE_INT) === false) {
            $this->error['days'] = 'days is not a integer';
        } elseif ($this->days < 1) {
            $this->error['days'] = 'Amount of days can`t be less 1 day';
        } elseif ($countDaysValid) {
            $this->error['days'] = 'You can take ' . ($allDays - $used) . ' days.';
        }
    }

    protected function validateStatus()
    {
        if (filter_var($this->status, FILTER_VALIDATE_INT) === false) {
            $this->error['status'] = 'Status incorrect';
        } elseif (!in_array($this->status, Vacation::STATUSES)) {
            $this->error['status'] = 'Status incorrect';
        }
    }

    public function load($data = [])
    {
        if (empty($data)) {
            return false;
        }

        foreach ($data as $key => $attr) {
            $this->{$key} = $attr;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @return bool|string
     */
    public function getError(string $attribute)
    {
        return $this->error[$attribute] ?? false;
    }

    /**
     * @return int
     */
    public function getUsedDays(): int
    {
        return (int)$this->model->getUsedDaysInYear($this->user->getId());
    }

    /**
     * @throws \Exception
     */
    public function add()
    {
        $this->model->add($this->user->getId(), $this->days, $this->status);
    }

    /**
     * @param int $id
     */
    public function update(int $id)
    {
        $this->model->update($id, $this->user->getId(), $this->days, $this->status);
    }

    /**
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->model->delete($id);
    }
}
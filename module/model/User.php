<?php

namespace module\model;

use library\http\Request;

class User
{
    public $id;
    public $name;

    public static function tableName(): string
    {
        return 'user';
    }

    public function __construct()
    {
        $request = new Request();
        $this->id = $request->getQueryParam('userId', 1);
    }

    public function getId()
    {
        return $this->id;
    }
}
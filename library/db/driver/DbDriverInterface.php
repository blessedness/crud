<?php

namespace library\db\driver;

interface DbDriverInterface
{
    public function getInstance();

    public function isConnected();
}
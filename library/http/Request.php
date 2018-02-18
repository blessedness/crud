<?php

namespace library\http;

class Request
{
    /**
     * @var string entry route point
     */
    protected $query = 'r';

    public function getQueryParams()
    {
        return $_GET;
    }

    public function getBodyParams()
    {
        return $_POST ?: null;
    }

    public function getPath()
    {
        return $this->getQueryParam($this->query);
    }

    public function getQueryParam(string $name, $default = null)
    {
        $request = $this->getQueryParams();

        return $request[$name] ?? $default;
    }

    public function isPost()
    {
        return $this->getBodyParams() ? true : false;
    }
}
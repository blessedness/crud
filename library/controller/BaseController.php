<?php

namespace library\controller;

use library\http\Request;
use library\http\Response;

/**
 * @property Request $request
 * @property Response $response
 */
abstract class BaseController
{
    /**
     * @var array
     */
    private $definitions;

    /**
     * @var array
     */
    private $components;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     *
     */
    public function init()
    {
        $this->fillDefinitions();
    }

    /**
     * Fill definitions
     */
    protected function fillDefinitions()
    {
        $components = $this->components();

        foreach ($components as $key => $component) {
            $this->definitions[$key] = $component;
        }
    }

    /**
     * @return array
     */
    protected function components()
    {
        return [
            'request' => [
                'class' => Request::class
            ],
            'response' => [
                'class' => Response::class
            ],
        ];
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->get('request');
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->get('response');
    }

    /**
     * @param string $id
     * @return object|null
     */
    public function get(string $id)
    {
        if (isset($this->components[$id])) {
            return $this->components[$id];
        }

        if (isset($this->definitions[$id])) {
            $definition = $this->definitions[$id]['class'];
            return $this->components[$id] = new $definition();
        } else {
            return null;
        }
    }

    public function __get($name)
    {
        $getter = 'get' . $name;

        if (method_exists($this, $getter)) {
            return $this->$getter();
        }

        throw new \Exception('Called unknown property: ' . $name);
    }
}
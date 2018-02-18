<?php

namespace library\http;


class Response
{
    private $output;

    public function redirect($url, $status = 302)
    {
        header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $url), true, $status);
        exit();
    }

    public function setOutput($output)
    {
        $this->output = $output;
    }

    public function output()
    {
        if ($this->output) {
            return $this->output;
        }
    }
}
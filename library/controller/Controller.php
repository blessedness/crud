<?php

namespace library\controller;

class Controller extends BaseController
{
    /**
     * @param string $action
     */
    public function redirect(string $action)
    {
        $this->response->redirect($action);
    }

    /**
     * @param string $file
     * @param array $params
     */
    public function render(string $file, array $params = [])
    {
        $this->renderFile($file, $params);

        return $this->response->output();
    }

    /**
     * @param string $fileName
     * @param array $params
     * @return string
     */
    protected function renderFile(string $fileName, array $params = [])
    {
        $file = ROOT . '/module/views/' . $fileName . '.php';

        try {
            if (!file_exists($file)) {
                throw new \ErrorException('View file don`t exist: ' . $file);
            }

            ob_start();
            ob_implicit_flush(false);
            extract($params, EXTR_OVERWRITE);

            require $file;

            $output = $this->response->setOutput(ob_get_clean());
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return $output;
    }
}
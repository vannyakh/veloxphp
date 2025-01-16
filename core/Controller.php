<?php

namespace Core;

abstract class Controller
{
    protected View $view;
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->view = new View();
        $this->request = Application::getInstance()->request;
        $this->response = Application::getInstance()->response;
    }

    protected function render(string $view, array $params = []): string
    {
        return $this->view->render($view, $params);
    }

    protected function json($data): string
    {
        $this->response->setHeader('Content-Type', 'application/json');
        return json_encode($data);
    }
} 
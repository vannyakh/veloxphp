<?php

namespace Core;

class View
{
    private string $layoutContent = '';
    private array $sections = [];
    private string $currentSection = '';

    public function render(string $view, array $params = []): string
    {
        $viewContent = $this->renderView($view, $params);
        
        if (empty($this->layoutContent)) {
            return $viewContent;
        }

        return str_replace('{{content}}', $viewContent, $this->layoutContent);
    }

    public function layout(string $layout): void
    {
        $this->layoutContent = $this->renderFile(
            Application::getInstance()->rootPath . "/views/layouts/$layout.php"
        );
    }

    public function section(string $name): void
    {
        $this->currentSection = $name;
        ob_start();
    }

    public function endSection(): void
    {
        if (!empty($this->currentSection)) {
            $this->sections[$this->currentSection] = ob_get_clean();
            $this->currentSection = '';
        }
    }

    public function yield(string $section): string
    {
        return $this->sections[$section] ?? '';
    }

    private function renderView(string $view, array $params): string
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include Application::getInstance()->rootPath . "/views/$view.php";
        return ob_get_clean();
    }

    private function renderFile(string $filepath): string
    {
        ob_start();
        include $filepath;
        return ob_get_clean();
    }
} 
<?php

namespace Core\View;

class View
{
    private string $viewPath;
    private string $layoutPath;
    private array $shared = [];
    private ?string $layout = null;
    private array $sections = [];
    private string $currentSection = '';

    public function __construct()
    {
        $this->viewPath = app()->rootPath . '/resources/views';
        $this->layoutPath = $this->viewPath . '/layouts';
    }

    public function render(string $view, array $data = []): string
    {
        $content = $this->renderView($view, array_merge($this->shared, $data));
        
        if ($this->layout) {
            return $this->renderLayout($content);
        }

        return $content;
    }

    public function share(string $key, $value): void
    {
        $this->shared[$key] = $value;
    }

    public function layout(string $name): void
    {
        $this->layout = $name;
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

    public function component(string $name, array $data = []): string
    {
        $path = $this->viewPath . '/components/' . str_replace('.', '/', $name) . '.php';
        return $this->renderFile($path, $data);
    }

    private function renderView(string $view, array $data): string
    {
        $path = $this->viewPath . '/' . str_replace('.', '/', $view) . '.php';
        return $this->renderFile($path, $data);
    }

    private function renderLayout(string $content): string
    {
        $this->sections['content'] = $content;
        $path = $this->layoutPath . '/' . $this->layout . '.php';
        return $this->renderFile($path, $this->shared);
    }

    private function renderFile(string $path, array $data): string
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("View file not found: {$path}");
        }

        extract($data);
        ob_start();
        include $path;
        return ob_get_clean();
    }
} 
<?php

if (!function_exists('app')) {
    function app($abstract = null) {
        if (is_null($abstract)) {
            return \Core\Application::getInstance();
        }
        return \Core\Application::getInstance()->container->get($abstract);
    }
}

if (!function_exists('config')) {
    function config($key = null, $default = null) {
        if (is_null($key)) {
            return app()->config;
        }
        return app()->config->get($key, $default);
    }
}

if (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('view')) {
    function view($view, $params = []) {
        return app()->view->render($view, $params);
    }
}

if (!function_exists('redirect')) {
    function redirect($path) {
        header("Location: {$path}");
        exit;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        return app()->session->get('_token');
    }
} 
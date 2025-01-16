<?php

// Static file handler
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    $ext = pathinfo($uri, PATHINFO_EXTENSION);
    $mime_types = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
    ];

    if (isset($mime_types[$ext])) {
        header('Content-Type: ' . $mime_types[$ext]);
        readfile(__DIR__ . $uri);
        exit;
    }
}

// Handle PHP requests through index.php
require __DIR__ . '/index.php'; 
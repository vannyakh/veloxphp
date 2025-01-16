<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Core\Application;

// Initialize the application
$app = new Application(dirname(__DIR__));

// Load configurations
$app->config->load();

// Run the application
$app->run(); 
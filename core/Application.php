<?php

namespace Core;

use Dotenv\Dotenv;

class Application
{
    private static Application $instance;
    public Router $router;
    public Request $request;
    public Response $response;
    public Container $container;
    public Config $config;
    public string $rootPath;

    public function __construct(string $rootPath)
    {
        self::$instance = $this;
        $this->rootPath = $rootPath;
        
        // Load environment variables
        $this->loadEnvironmentVariables();
        
        // Initialize core components
        $this->container = new Container();
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->config = new Config($rootPath . '/config');
        
        // Register core services
        $this->registerCoreServices();
    }

    private function loadEnvironmentVariables(): void
    {
        $dotenv = Dotenv::createImmutable($this->rootPath);
        $dotenv->load();
        
        // Required environment variables
        $dotenv->required([
            'APP_NAME',
            'APP_ENV',
            'DB_HOST',
            'DB_DATABASE',
            'DB_USERNAME'
        ]);
    }

    public static function getInstance(): Application
    {
        return self::$instance;
    }

    private function registerCoreServices(): void
    {
        $this->container->singleton(Request::class, $this->request);
        $this->container->singleton(Response::class, $this->response);
        $this->container->singleton(Router::class, $this->router);
    }

    public function run(): void
    {
        try {
            $this->router->resolve();
        } catch (\Exception $e) {
            $this->response->setStatusCode(500);
            echo $this->handleException($e);
        }
    }
} 
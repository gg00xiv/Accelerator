<?php

namespace Accelerator;

use Accelerator\Model\GenericDatabase;
use Accelerator\Config;
use Accelerator\View\View;
use Accelerator\AcceleratorException;

/**
 * Description of Application
 *
 * @author gg00xiv
 */
class Application {

    private $config;
    private $dbConnection;
    private $entityConfigs;
    private static $_instance = null;
    private $views;
    private $layouts;
    private $controllers;
    private $routeHandlers;

    /**
     * Application singleton.
     * 
     * @return \Accelerator\Application
     */
    public static function instance() {
        if (self::$_instance === null)
            self::$_instance = new Application();
        return self::$_instance;
    }

    /**
     * Get the website name defined in configuration file.
     * 
     * @return string Website name.
     */
    public function getWebsiteName() {
        return $this->config->global->website_name;
    }

    /**
     * Get the website base URL defined in configuration file.
     * 
     * @return string Website base URL.
     */
    public function getBaseUrl() {
        return $this->config->global->base_url;
    }

    public function getPageSize() {
        return $this->config->global->page_size;
    }

    public function getPageParameter() {
        return $this->config->global->page_parameter;
    }

    /**
     * Get a registered view by its name.
     * 
     * @param string $viewName The name a the registered view.
     * @return \Accelerator\View
     */
    public function getView($viewName) {
        return $this->views[$viewName];
    }

    public function getEntityConfig($entity) {
        $rClass = new \ReflectionClass($entity);
        $className = $rClass->getName();

        return $this->entityConfigs[$className];
    }

    /**
     * Get the database connection configured in Applicatin.
     * 
     * @return \Accelerator\Model\Driver\DbConnection DbConnection instance.
     */
    public function getDbConnection() {
        return $this->dbConnection;
    }

    /**
     * Load Application config.
     * 
     * @param array $config
     * @return \Accelerator\Application 
     */
    public function init($config) {
        if ($this->config)
            return;
        if (!is_array($config))
            throw new AcceleratorException('$config parameter must be an array.');

        $this->config = new Config($config);

        $gdb = new GenericDatabase($this->config->model->connection);
        $this->dbConnection = $gdb->getConnection();
        $this->dbConnection->open();

        $this->loadEntityMaps();
        $this->loadViews();
        $this->loadControllers();
        $this->loadRoutes();

        return $this;
    }

    /**
     * Dispatch current request to the right controller.
     * 
     * @return \Accelerator\Application The Application instance.
     * @throws AcceleratorException If the request path is invalid.
     */
    public function dispatch() {

        $full_route_uri = 'http://' . $_SERVER['SERVER_NAME'] . '/' . ltrim($_SERVER['REQUEST_URI'], '/');
        if (substr($full_route_uri, 0, strlen($this->config->global->base_url)) != $this->config->global->base_url)
            throw new AcceleratorException('Invalid script base url : ' . $full_route_uri);

        $routePath = '/' . trim(substr($full_route_uri, strlen($this->config->global->base_url)), '/');

        foreach ($this->routeHandlers as $route => $routeHandler) {
            $routePattern = '|^' . preg_replace(array('/(\?|\.)/', '/\[:(\w+)\]/i'), array('\\\\$1', '(?<$1>[^&/]*)'), $route) . '$|i';

            if (preg_match($routePattern, $routePath, $matches)) {
                $parameters = new \ArrayObject($matches, \ArrayObject::ARRAY_AS_PROPS);
                $controller = $routeHandler[0];
                $view = $routeHandler[1];
                $controller->execute($view, $parameters);
                return $this;
            }
        }
        throw new AcceleratorException('Invalid route path, no controller defined for route : ' . $routePath);
    }

    private function loadEntityMaps() {
        $this->entityConfig = array();
        foreach ($this->config->model->entities as $entityClass => $entityConfig) {
            $this->entityConfigs[$entityClass] = $entityConfig;
        }
    }

    private function loadViews() {
        $this->views = array();
        $this->layouts = array();

        foreach ($this->config->views->map as $viewName => $viewConfig) {
            if (is_string($viewConfig)) {
                $path = rtrim($this->config->views->path, '/') . '/' . trim($viewConfig, '/');
                $view = new View($path);
            } else if ($viewConfig instanceof \ArrayObject && isset($viewConfig->file)) {
                $path = rtrim($this->config->views->path, '/') . '/' . trim($viewConfig->file, '/');
                $view = new View($path, $viewConfig->parent, $viewConfig->pageSize);
            }

            if ($view)
                $this->views[$viewName] = $view;
        }
    }

    private function loadControllers() {
        $this->controllers = array();
        $controllerNamespace = rtrim($this->config->global->namespace, '\\') . '\\' . trim($this->config->controllers->namespace, '\\');
        if (!$this->config->controllers || !$this->config->controllers->list || !is_array($this->config->controllers->list) || count($this->config->controllers->list) == 0)
            throw new AcceleratorException('No controller defined in config.');

        foreach ($this->config->controllers->list as $controllerClass) {
            $controllerClassPath = $controllerNamespace . '\\' . trim($controllerClass, '\\');
            $pathParts = explode('\\', $controllerClassPath);
            $controllerClass = $pathParts[count($pathParts) - 1];
            $this->controllers[$controllerClass] = new $controllerClassPath();
        }
    }

    private function loadRoutes() {
        $this->routeHandlers = array();
        foreach ($this->config->routes as $route => $routeHandler) {
            $routePath = '/' . trim($route, '/');

            if ((!is_array($routeHandler) && !$routeHandler instanceof \ArrayObject) || count($routeHandler) != 2)
                throw new AcceleratorException('Invalid route handler for route : ' . $route);

            $controller = $this->controllers[isset($routeHandler->controller) ? $routeHandler->controller : $routeHandler[0]];
            $view = $this->views[isset($routeHandler->view) ? $routeHandler->view : $routeHandler[1]];
            $this->routeHandlers[$routePath] = array($controller, $view);
        }
    }

}

?>

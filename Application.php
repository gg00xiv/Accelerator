<?php

namespace EasyMvc;

use EasyMvc\Model\GenericDatabase;
use EasyMvc\Config;
use EasyMvc\View\View;

/**
 * Description of Application
 *
 * @author gghez
 */
class Application {

    private $config;
    private $dbConnection;
    private $entityConfigs;
    private static $_instance = null;
    private $views;
    private $layouts;
    private $controllers;
    private $routeToController;

    /**
     * Application singleton.
     * 
     * @return \EasyMvc\Application
     */
    public static function instance() {
        if (self::$_instance === null)
            self::$_instance = new Application();
        return self::$_instance;
    }

    /**
     * Get a registered view by its name.
     * 
     * @param string $viewName The name a the registered view.
     * @return \EasyMvc\View
     */
    public function getView($viewName) {
        return $this->views[$viewName];
    }

    public function getEntityConfig($entity) {
        $rClass = new \ReflectionClass($entity);
        $className = $rClass->getName();

        return $this->entityConfigs[$className];
    }

    public function getDbConnection() {
        return $this->dbConnection;
    }

    public function init($config) {
        if ($this->config)
            return;
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

    private function loadEntityMaps() {
        $path = $this->config->model->entity_path;
        $files = glob($path . '/*.php');
        $this->entityConfig = array();
        foreach ($files as $file) {
            $config = new Config(include $file);
            $this->entityConfigs[$config->class] = $config;
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
                $view = new View($path, $viewConfig->parent);
            }

            if ($view)
                $this->views[$viewName] = $view;
        }
    }

    private function loadControllers() {
        $this->controllers = array();
        $controllerNamespace = rtrim($this->config->global->namespace, '\\') . '\\' . trim($this->config->controllers->namespace, '\\');
        foreach ($this->config->controllers->map as $controllerClass => $viewName) {
            $controllerClassPath = $controllerNamespace . '\\' . trim($controllerClass, '\\');
            $view = $this->views[$viewName];
            $controller = new $controllerClassPath($view);
            $this->controllers[$controllerClass] = $controller;
        }
    }

    private function loadRoutes() {
        $this->routeToController = array();
        foreach ($this->config->routes as $route => $controller) {
            $routePath = '/' . trim($route, '/');
            $this->routeToController[$routePath] = $this->controllers[$controller];
        }
    }

    /**
     * Get controller from route path.
     * 
     * @param \EasyMvc\Controller\Controller $routePath
     */
    private function executeControllerFromRoutePath($routePath) {
        foreach ($this->routeToController as $route => $controller) {
            $routePattern = '|^' . preg_replace('|\[:(.+?)\]|i', '(?P<$1>[a-z0-9\-_]+?)', $route) . '$|i';
            if (preg_match($routePattern, $routePath, $matches)) {
                $parameters = array();
                foreach ($matches as $parameterName => $parameterValue) {
                    if (!is_string($parameterName))
                        continue;
                    $parameters[$parameterName] = $parameterValue;
                }
                $controller->execute($parameters);
                $controller->getView()->render();
                return $controller;
            }
        }
        throw new EasyMvcException('Invalid route path, no controller defined : ' . $routePath);
    }

    /**
     * Dispatch current request to the right controller.
     * 
     * @return \EasyMvc\Application The Application instance.
     * @throws EasyMvcException If the request path is invalid.
     */
    public function dispatch() {

        $full_route_uri = $_SERVER['SCRIPT_URI'];
        if (substr($full_route_uri, 0, strlen($this->config->global->base_url)) != $this->config->global->base_url)
            throw new EasyMvcException('Invalid script base url.');

        $routePath = '/' . trim(substr($full_route_uri, strlen($this->config->global->base_url)), '/');
        $this->executeControllerFromRoutePath($routePath);

        return $this;
    }

}

?>

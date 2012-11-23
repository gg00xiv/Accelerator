<?php

namespace Accelerator;

use Accelerator\Model\GenericDatabase;
use Accelerator\Config;
use Accelerator\View\View;
use Accelerator\AcceleratorException;
use Accelerator\Helper\ResponseHelper;

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
    private $session;

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
     * Returns the current session used by application.
     * 
     * @return Accelerator\Session
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * Returns the default contact email for this website.
     * 
     * @return string
     */
    public function getContactEmail() {
        return $this->config->global->contact_email;
    }

    /**
     * Returns the cache configuration section.
     * 
     * @return Accelerator\Config
     */
    public function getCacheConfig() {
        return $this->config->cache;
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

    /**
     * Returns a full URL based on base url defined in configuration file.
     * 
     * @param string $relativeUrl
     * @return string
     */
    public function getCompleteUrl($relativeUrl) {
        return rtrim($this->getBaseUrl(), '/') . '/' . ltrim($relativeUrl, '/');
    }

    /**
     * Get the number of items to display per page in pagination context.
     * 
     * @return int Number of items per page to display.
     */
    public function getItemsPerPage() {
        return $this->config->global->pagination->items_per_page;
    }

    /**
     * Get the page parameter name from route that identify the current page index.
     * 
     * @return string Page parameter name.
     */
    public function getPageParameter() {
        return $this->config->global->pagination->page_parameter;
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
     * Load application configuration and start dispatching current request.
     * 
     * @param array $config
     * @return \Accelerator\Application
     */
    public function run(array $config) {

        $this->config = new Config($config);

        if ($this->config->log)
            Log\LogManager::init($this->config->log);

        $gdb = new GenericDatabase($this->config->model->connection);
        $this->dbConnection = $gdb->getConnection();
        $this->dbConnection->open();

        $this->loadEntityMaps();
        $this->loadViews();
        $this->loadControllers();
        $this->loadRoutes();

        $this->session = new Session();
        if ($this->config->global->autostart_session)
            $this->session->start();

        $this->dispatch();

        return $this;
    }

    /**
     * Dispatch current request to the right controller.
     * 
     * @return \Accelerator\Application The Application instance.
     * @throws Exception\ConfigurationException If the request path is invalid.
     */
    private function dispatch() {
        $full_route_uri = 'http://' . $_SERVER['SERVER_NAME'] . '/' . ltrim($_SERVER['REQUEST_URI'], '/');
        if (substr($full_route_uri, 0, strlen($this->getBaseUrl())) != $this->getBaseUrl())
            throw new Exception\ConfigurationException('Invalid script base url for : ' . $full_route_uri);

        $routePath = '/' . ltrim(substr($full_route_uri, strlen($this->getBaseUrl())), '/');

        foreach ($this->routeHandlers as $route => $routeHandler) {
            $patterns = array('/(\?|\.)/', '/:([a-z]+)/i', '/\((.+)\)/');
            $replacements = array('\\\\$1', '?<$1>[^&/]*', '($1)?');
            $routePattern = '|^' . preg_replace($patterns, $replacements, $route) . '$|i';

            if (preg_match($routePattern, $routePath, $matches)) {
                $parameters = new \ArrayObject(array(), \ArrayObject::ARRAY_AS_PROPS);
                foreach ($matches as $name => $value)
                    $parameters->$name = urldecode($value);
                $controller = $routeHandler[0];
                $view = $routeHandler[1];
                $controller->execute($view, $parameters);
                return $this;
            }
        }

        ResponseHelper::notFound();
    }

    private function loadEntityMaps() {
        $this->entityConfig = array();
        if ($this->config->model->entities) {
            foreach ($this->config->model->entities as $entityClass => $entityConfig) {
                $this->entityConfigs[$entityClass] = $entityConfig;
            }
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
                $view = new View($path, $viewConfig->parent, $viewConfig->items_per_page);
            }

            if ($view)
                $this->views[$viewName] = $view;
        }
    }

    private function loadControllers() {
        $this->controllers = array();
        $controllerNamespace = rtrim($this->config->global->namespace, '\\') . '\\' . trim($this->config->controllers->namespace, '\\');
        if (!$this->config->controllers || !$this->config->controllers->list || !is_array($this->config->controllers->list) || count($this->config->controllers->list) == 0)
            throw new Exception\ConfigurationException('No controller defined in config.');

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

            $viewName = null;
            if (is_string($routeHandler))
                $controllerName = $routeHandler;
            else if (is_array($routeHandler) || $routeHandler instanceof \ArrayObject) {
                // read controller
                if (isset($routeHandler->controller))
                    $controllerName = $routeHandler->controller;
                else if (isset($routeHandler[0]))
                    $controllerName = $routeHandler[0];

                // read view
                if (isset($routeHandler->view))
                    $viewName = $routeHandler->view;
                else if (isset($routeHandler[1]))
                    $viewName = $routeHandler[1];

                // read static parameters
                // TODO
            }

            if (!$controllerName)
                $controller = new Controller\DefaultController ();
            else if ($controllerName[0] == '\\')
                $controller = new $controllerName ();
            else
                $controller = $this->controllers[$controllerName];

            $view = $viewName ?
                    $this->views[$viewName] :
                    null;
            $this->routeHandlers[$routePath] = array($controller, $view);
        }
    }

}

?>

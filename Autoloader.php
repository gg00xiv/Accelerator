<?php

namespace Accelerator;

/**
 * Description of Autoloader
 *
 * @author gg00xiv
 */
abstract class Autoloader {

    const NS_SEPARATOR = '\\';
    const PATH_SEPARATOR = '/';

    private static $namespaces;

    /**
     * Register a set of namespace for class autoloading. Each class in those namespaces
     * will be autoloaded when called.
     * 
     * @param array $namespaces Autoloaded class namespaces.
     */
    public static function register(array $namespaces) {
        static::$namespaces = $namespaces;
        spl_autoload_register(array('\Accelerator\Autoloader', 'autoload'));
    }

    /**
     * Autoload a class given its full namespaced name.
     * 
     * @param string $classpath Full class name.
     */
    public static function autoload($classpath) {
        foreach (static::$namespaces as $namespace => $paths) {
            if (is_string($paths))
                $paths = array($paths);
            foreach ($paths as $path) {
                $namespace = trim($namespace, static::NS_SEPARATOR);
                if ($namespace == trim(substr($classpath, 0, strlen($namespace)), static::NS_SEPARATOR)) {
                    $child_classpath = substr($classpath, strlen($namespace));
                    $child_path = $path . str_replace(static::NS_SEPARATOR, static::PATH_SEPARATOR, $child_classpath) . '.php';

                    if (!file_exists($child_path)) {
                        $child_path = strtolower(dirname($child_path))
                                . static::PATH_SEPARATOR
                                . basename($child_path);
                    }
                    if (file_exists($child_path))
                        include_once $child_path;
                }
            }
        }
    }

}

?>

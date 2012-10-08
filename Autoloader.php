<?php

namespace EasyMvc;

/**
 * Description of Autoloader
 *
 * @author gghez
 */
abstract class Autoloader {

    const NS_SEPARATOR = '\\';
    const PATH_SEPARATOR = '/';

    private static $namespaces;

    public static function register(array $namespaces) {
        static::$namespaces = $namespaces;
        spl_autoload_register(array('\EasyMvc\Autoloader', 'autoload'));
    }

    public static function autoload($classpath) {
        foreach (static::$namespaces as $namespace => $path) {
            $namespace = trim($namespace, static::NS_SEPARATOR);
            if ($namespace == trim(substr($classpath, 0, strlen($namespace)), static::NS_SEPARATOR)) {
                $child_classpath = substr($classpath, strlen($namespace));
                $child_path = $path . str_replace(static::NS_SEPARATOR, static::PATH_SEPARATOR, $child_classpath) . '.php';

                if (!file_exists($child_path)) {
                    $child_path = strtolower(dirname($child_path))
                            . static::PATH_SEPARATOR
                            . basename($child_path);
                }
                include_once $child_path;
            }
        }
    }

}

?>

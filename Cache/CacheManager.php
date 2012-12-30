<?php

namespace Accelerator\Cache;

/**
 * Description of CacheManager
 *
 * @author gg00xiv
 */
class CacheManager {

    private $config;
    private $innerCache;

    private function __construct() {
        $this->config = \Accelerator\Application::instance()->getCacheConfig();
        $this->innerCache = new MemoryCache();
    }

    private static $instance = null;

    /**
     * Returns the singleton of CacheManager class.
     * 
     * @return \Accelerator\Cache\CacheManager
     */
    public static function instance() {
        if (self::$instance === null)
            self::$instance = new CacheManager();
        return self::$instance;
    }

    /**
     * Get a default cache instance for any storing purpose.
     * 
     * @return \Accelerator\Cache\MemoryCache 
     */
    public function getDefaultCache() {
        $defaultCacheKey = 'default-cache';
        if (($cache = $this->innerCache->get($defaultCacheKey)) == null) {
            $cache = new MemoryCache();
            $this->innerCache->put($defaultCacheKey, $cache);
        }
        return $cache;
    }

    /**
     * Get a cache by its name defined in application configuration file.
     * 
     * @param string $cacheName
     * @return \Accelerator\Cache\Cache
     * @throws \Accelerator\Exception\ConfigurationException 
     */
    public function getCache($cacheName) {
        if (!isset($this->config->$cacheName))
            throw new \Accelerator\Exception\ConfigurationException('No configuration found for cache named : ' . $cacheName);

        $innerCacheKey = 'cache-' . $cacheName;

        if (($cache = $this->innerCache->get($innerCacheKey)) == null) {

            switch ($this->config->$cacheName->mode) {
                case 'file':
                    $cache = new FileCache($this->config->$cacheName->path);
                    break;

                case 'memory':
                    $cache = new MemoryCache();
                    break;

                default:
                    throw new \Accelerator\Exception\ConfigurationException('Invalid cache mode specified in configuration : ' . $this->config->$cacheName->mode);
            }

            if (is_int($this->config->$cacheName->lifetime) && $this->config->$cacheName->lifetime > 0)
                $cache->setDefaultLifetime($this->config->$cacheName->lifetime);
            
            $this->innerCache->put($innerCacheKey, $cache);
        }

        return $cache;
    }

}

?>
<?php

namespace Accelerator\Cache;

/**
 * Description of Cache
 *
 * @author gg00xiv
 */
abstract class Cache {

    private $defaultLifetime = 0;

    /**
     * Get the cache default lifetime for object storing.
     * 
     * @return int
     */
    public function getDefaultLifetime() {
        return $this->defaultLifetime;
    }

    /**
     * Set the default lifetime for cache item storing.
     * 
     * @param int $lifetime
     * @throws \Accelerator\Exception\ArgumentException 
     */
    public function setDefaultLifetime($lifetime) {
        if (!is_int($lifetime))
            throw new \Accelerator\Exception\ArgumentException('$lifetime', 'Invalid type. Only interger allowed.');

        $this->defaultLifetime = $lifetime;
    }

    /**
     * @return Accelerator\Cache\CacheItem 
     */
    public abstract function getCacheItem($key);

    public function get($key) {
        $item = $this->getCacheItem($key);
        return $item === null || $item->hasExpired() ? null : $item->getData();
    }

    /**
     * @return Accelerator\Cache\CacheItem 
     */
    public abstract function put($key, $data, $lifetime = null);
    
    /**
     * @return Accelerator\Cache\CacheItem 
     */
    public abstract function remove($key);
}

?>
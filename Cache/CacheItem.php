<?php

namespace Accelerator\Cache;

/**
 * Description of CacheItem
 *
 * @author gg00xiv
 */
class CacheItem {

    private $key;
    private $data;
    private $expiration;

    /**
     * Create a CacheItem object from its key, data and expiration time.
     * 
     * @param string $key
     * @param mixed $data
     * @param int $expiration
     * @throws \Accelerator\Exception\ArgumentException 
     */
    public function __construct($key, $data, $expiration = 0) {
        if (!is_int($expiration))
            throw new \Accelerator\Exception\ArgumentException('$expiration', 'Invalid value type. Only integer allowed.');

        $this->key = $key;
        $this->data = $data;
        $this->expiration = $expiration;
    }

    /**
     * Get the cache item key.
     * 
     * @return string 
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * Get the data contained by this cache item.
     * 
     * @return mixed 
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Get the expiration time of this cache item.
     * 
     * @return int (same as time() return)
     */
    public function getExpiration() {
        return $this->expiration;
    }

    /**
     * Checks whether this cache item has expired or not.
     * 
     * @return boolean 
     */
    public function hasExpired() {
        return time() > $this->expiration;
    }

}

?>
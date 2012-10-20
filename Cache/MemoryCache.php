<?php

namespace Accelerator\Cache;

/**
 * Description of MemoryCache
 *
 * @author gg00xiv
 */
class MemoryCache extends Cache {

    private $hashMap;

    public function __construct() {
        $this->hashMap = array();
    }

    public function getCacheItem($key) {
        if (!array_key_exists($key, $this->hashMap))
            return null;

        $data = $this->hashMap[$key];
        if ($data['expiration'] && time() > $data['lifetime']) {
            unset($this->hashMap[$key]);
            return null;
        }

        return $data['data'];
    }

    public function put($key, $data, $lifetime = null) {
        if ($lifetime === null)
            $lifetime = $this->getDefaultLifetime();

        $item = new CacheItem($key, $data, time() + $lifetime);
        $this->hashMap[$key] = $item;

        return $item;
    }

}

?>
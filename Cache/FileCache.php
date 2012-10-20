<?php

namespace Accelerator\Cache;

/**
 * Description of FileCache
 *
 * @author gg00xiv
 */
class FileCache extends Cache {

    const FILE_FORMAT = 'cache_item_$key.dat';

    private $cachePath;

    public function __construct($cachePath) {
        $this->cachePath = rtrim($cachePath, '/') . '/';
    }

    private function getFileName($key) {
        return $this->cachePath . str_replace('$key', $key, self::FILE_FORMAT);
    }

    public function getCacheItem($key) {
        $cacheFilename = self::getFileName($key);
        if (!file_exists($cacheFilename))
            return null;

        $raw = file_get_contents($cacheFilename);
        parse_str($raw, $data);

        $item = new CacheItem($key, $data['data'], intval($data['expiration']));

        // If content has expired, destroy item cache file.
        if ($item->hasExpired()) {
            unlink($cacheFilename);
        }

        return $item;
    }

    public function put($key, $data, $lifetime = null) {
        if ($lifetime === null)
            $lifetime = $this->getDefaultLifetime();

        $item = new CacheItem($key, $data, time() + $lifetime);

        $cacheFilename = self::getFileName($key);
        $raw = http_build_query(array('data' => $data, 'expiration' => $item->getExpiration()));

        file_put_contents($cacheFilename, $raw);

        return $item;
    }

}

?>
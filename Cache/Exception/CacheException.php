<?php

namespace Accelerator\Cache\Exception;
/**
 * Description of CacheException
 *
 * @author gg00xiv
 */
class CacheException extends \Exception {

    public function __construct($message) {
        parent::__construct($message);
    }

}

?>
<?php

namespace EasyMvc;

/**
 * Description of Config
 *
 * @author gg00xiv
 */
class Config extends \ArrayObject {

    public function __construct(array $config) {
        parent::__construct($config, static::ARRAY_AS_PROPS);
        foreach ($config as $key => $value) {
            if (is_array($value) && !array_key_exists(0, $value))
                $this->$key = new Config($value);
        }
    }

}

?>

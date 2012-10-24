<?php

namespace Accelerator\Log;

/**
 * Description of LogManager
 *
 * @author gg00xiv
 */
abstract class LogManager {

    private static $loggers;

    /**
     * Initialize from configuration section.
     * 
     * @param \ArrayObject $config
     * @throws \Accelerator\Exception\ConfigurationException
     */
    public static function init(\ArrayObject $config) {
        self::$loggers = array();
        foreach ($config as $loggerName => $loggerParams) {
            $loggerClass = '\Accelerator\Log\\' . ucfirst(strtolower($loggerParams->type)) . 'Logger';
            try {
                $logger = new $loggerClass($loggerParams->params);
                self::$loggers[$loggerName] = $logger;
            } catch (\Accelerator\Exception\AutoloadException $ex) {
                throw new \Accelerator\Exception\ConfigurationException('Invalid logger type specified : ' . $loggerParams->type . ' - ' . $ex->getMessage());
            }
        }
    }

    /**
     * Get a logger by its named configured in application configuration file.
     * 
     * @param string $name
     * @return Accelerator\Log\Logger
     * @throws Exception\LogException
     */
    public static function getLogger($name) {
        if (!array_key_exists($name, self::$loggers))
            throw new Exception\LogException('Logger not found : ' . $name);
        return self::$loggers[$name];
    }

}

?>

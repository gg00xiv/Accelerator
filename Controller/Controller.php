<?php

namespace Accelerator\Controller;

/**
 * The base class for all Controller specified classes.
 *
 * @author gg00xiv
 */
abstract class Controller {

    /**
     * Get the current Application context.
     * 
     * @return \Accelerator\Application Application instance.
     */
    public function getApplication() {
        return \Accelerator\Application::instance();
    }

    /**
     * Run the controller code.
     * 
     * @param array $parameters URL parameters.
     */
    public abstract function execute(\Accelerator\View\View $view, \ArrayObject $parameters = null);
}

?>

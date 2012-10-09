<?php

namespace Accelerator\Controller;

use Accelerator\View\View;
/**
 * The base class for all Controller specified classes.
 *
 * @author gg00xiv
 */
abstract class Controller {

    /**
     * Run the controller code.
     * 
     * @param array $parameters URL parameters.
     */
    public abstract function execute(View $view, \ArrayObject $parameters = null);

}

?>

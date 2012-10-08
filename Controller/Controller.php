<?php

namespace Accelerator\Controller;

use Accelerator\AcceleratorException;

/**
 * The base class for all Controller specified classes.
 *
 * @author gg00xiv
 */
abstract class Controller {

    protected $view;
    protected $parameters;

    public function __construct($view) {
        if (!$view)
            throw new AcceleratorException('Invalid parameters.');

        $this->view = $view;
    }

    /**
     * Get the view associated to this controller instance.
     * 
     * @return \Accelerator\View The View associated to this controller
     */
    public function getView() {
        return $this->view;
    }

    /**
     * Run the controller code.
     * 
     * @param array $parameters URL parameters.
     */
    public function execute(array $parameters = null) {
        $this->parameters = new \ArrayObject($parameters ? : array(), \ArrayObject::ARRAY_AS_PROPS);
        $this->view->parameters = $this->parameters;
        $this->onExecute();
    }

    /**
     * Specified Controller code is executed from here.
     */
    protected abstract function onExecute();
}

?>

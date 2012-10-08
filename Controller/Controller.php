<?php

namespace EasyMvc\Controller;

use EasyMvc\EasyMvcException;

/**
 * Description of Controller
 *
 * @author gg00xiv
 */
abstract class Controller {

    protected $view;
    protected $parameters;

    public function __construct($view) {
        if (!$view)
            throw new EasyMvcException('Invalid parameters.');

        $this->view = $view;
    }

    /**
     * Get the view associated to this controller instance.
     * 
     * @return \EasyMvc\View The View associated to this controller
     */
    public function getView() {
        return $this->view;
    }

    public function execute(array $parameters = null) {
        $this->parameters = new \ArrayObject($parameters ? : array(), \ArrayObject::ARRAY_AS_PROPS);
        $this->view->parameters = $this->parameters;
        $this->onExecute();
    }

    protected abstract function onExecute();
}

?>

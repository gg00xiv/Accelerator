<?php

namespace Accelerator\Controller;

use Accelerator\View\Helper\ResponseHelper;

/**
 * The base class for all Controller specified classes.
 *
 * @author gg00xiv
 */
abstract class Controller {

    protected $view;
    protected $parameters;

    /**
     * Returns the page number.
     * 
     * @return int Page number (1 to n).
     */
    public function getPageIndex() {
        if ($this->pageIndex)
            return $this->pageIndex;

        $pageParameterName = $this->getApplication()->getPageParameter();
        if (!$this->parameters->$pageParameterName)
            return 1;
        $pageIndex = $this->parameters->$pageParameterName;
        if ($pageIndex <= 0)
            ResponseHelper::notFound();
        return $this->pageIndex = $pageIndex;
    }

    /**
     * Get the number of items that should be printed per page.
     * 
     * @return int Number of items per page.
     */
    public function getPageSize() {
        return $this->view->getPageSize();
    }

    /**
     * Get the first item index in paginated View context.
     */
    public function getPageFirstItemIndex() {
        return $this->getPageSize() * ($this->getPageIndex() - 1);
    }

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
     * @param \Accelerator\View\View View on which execute this controller.
     * @param \ArrayObject $parameters URL parameters.
     */
    public function execute(\Accelerator\View\View $view, \ArrayObject $parameters) {
        $this->view = $view;
        $this->parameters = $parameters;
        $this->onExecute();
        $view->render($this);
    }

    /**
     * This method have to be implemented on custom controller to handle request parameters.
     */
    protected abstract function onExecute();
}

?>

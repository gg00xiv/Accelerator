<?php

namespace Accelerator\View;

use Accelerator\AcceleratorException;
use Accelerator\Application;
use Accelerator\RequestHelper;

/**
 * A View represents the PHP code behind a user interface (here a web page).
 * A View can have a parent and a child (see application configuration file).
 *
 * @author gg00xiv
 */
class View {

    private $path;
    private $childView;
    private $parentViewName;
    private $title;
    private $description;
    private $helper;

    public function __construct($path, $parentViewName = null) {
        if (!is_string($path) || empty($path))
            throw new AcceleratorException('Invalid parameters.');

        $this->path = $path;
        if (is_string($parentViewName))
            $this->parentViewName = $parentViewName;

        $this->helper = RequestHelper::instance();
    }

    /**
     * Used in templates (*.phtml) files to get the most defined title in child
     * views.
     * 
     * @return string Child View title. 
     */
    public function getTitle() {
        if ($this->childView)
            return $this->childView->getTitle();
        return $this->title;
    }

    /**
     * Define the View title.
     * 
     * @param string $title The View title.
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Used in templates (*.phtml) files to get the most defined description in child
     * views.
     * 
     * @return string Child View description.
     */
    public function getDescription() {
        if ($this->childView)
            return $this->childView->getDescription();
        return $this->description;
    }

    /**
     * Defined the View description.
     * 
     * @param string $description The View description.
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Used by Application to render the controller linked View.
     * 
     * @throws AcceleratorException If a specified parent View was not found.
     */
    public function render() {
        if ($this->parentViewName) {
            $parentView = Application::instance()->getView($this->parentViewName);
            if (!$parentView)
                throw new AcceleratorException('Parent view not found : ' . $this->parentViewName);
            $parentView->childView = $this;
            $parentView->render();
        }
        else
            $this->renderSelf();
    }

    /**
     * Used in templates (*.phtml files) to render child content in place.
     */
    public function renderChild() {
        if ($this->childView)
            $this->childView->renderSelf();
    }

    private function renderSelf() {
        include $this->path;
    }

}

?>

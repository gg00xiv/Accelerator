<?php

namespace Accelerator\View;

use Accelerator\AcceleratorException;
use Accelerator\Application;

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
    private $parentView;

    /**
     * Create a View from file path and parent view name.
     * Parent view is lazy loaded to avoid errors when loading configuration file.
     * 
     * @param string $path View full path on disk.
     * @param string $parentViewName The parent view name defined in configuration file.
     * @throws \Accelerator\AcceleratorException
     */
    public function __construct($path, $parentViewName = null) {
        if (!is_string($path) || empty($path))
            throw new AcceleratorException('Invalid parameters.');

        if ($parentViewName && !is_string($parentViewName))
            throw new AcceleratorException('Invalid parent view name : ' . $parentViewName);

        $this->path = $path;
        $this->parentViewName = $parentViewName;
    }

    /**
     * Get the parent view of this View instance.
     * 
     * @return \Accelerator\View\View A View instance or nothing.
     * @throws \Accelerator\AcceleratorException
     */
    public function getParentView() {
        if (!$this->parentView && $this->parentViewName) {
            $this->parentView = Application::instance()->getView($this->parentViewName);
            if (!$this->parentView)
                throw new AcceleratorException('Parent view not found : ' . $this->parentViewName);
            $this->parentView->childView = $this;
        }
        return $this->parentView;
    }

    /**
     * Get the current Application context.
     * 
     * @return \Accelerator\Application Application instance.
     */
    public function getApplication() {
        return Application::instance();
    }

    /**
     * Used in templates (*.phtml) files to get the most defined title in child
     * views.
     * 
     * @return string Child View title. 
     */
    public function getTitle() {
        return $this->childView ?
                $this->childView->getTitle() :
                $this->title;
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
        if ($parentView = $this->getParentView())
            $parentView->render();
        else
            $this->renderSelf();
    }

    /**
     * Used in templates (*.phtml files) to display child content.
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

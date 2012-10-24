<?php

namespace Accelerator\View;

/**
 * A View represents the PHP code behind a user interface (here a web page).
 * A View can have a parent and a child (see application configuration file).
 *
 * @author gg00xiv
 */
class View {

    private $_path;
    private $_parentViewName;
    private $_title;
    private $_itemsPerPage;
    private $_description;
    private $_parentView;
    private $_renderViews;
    private $_headLinks;
    private $_headMetas;
    private $_headScripts;

    /**
     * Create a View from file path and parent view name.
     * Parent view is lazy loaded to avoid errors when loading configuration file.
     * 
     * @param string $path View full path on disk.
     * @param string $parentViewName The parent view name defined in configuration file.
     * @param string $pageParameter The page parameter name containing page index.
     * @param int $itemsPerPage The number of items per page.
     * @throws Html\Exception\HtmlException
     */
    public function __construct($path, $parentViewName = null, $itemsPerPage = null) {
        if (!is_string($path) || empty($path))
            throw new Html\Exception\HtmlException('Invalid parameters.');

        if ($parentViewName && !is_string($parentViewName))
            throw new Html\Exception\HtmlException('Invalid parent view name : ' . $parentViewName);

        $this->_path = $path;
        $this->_parentViewName = $parentViewName;
        $this->_itemsPerPage = $itemsPerPage;
    }

    public function getItemsPerPage() {
        return $this->_itemsPerPage? :
                ($this->_itemsPerPage = $this->getApplication()->getItemsPerPage());
    }

    /**
     * Get the parent view of this View instance.
     * 
     * @return \Accelerator\View\View A View instance or nothing.
     * @throws Html\Exception\HtmlException
     */
    public function getParentView() {
        if (!$this->_parentView && $this->_parentViewName) {
            $this->_parentView = $this->getApplication()->getView($this->_parentViewName);
            if (!$this->_parentView)
                throw new Html\Exception\HtmlException('Parent view not found : ' . $this->_parentViewName);
            $this->_parentView->_childView = $this;
        }
        return $this->_parentView;
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
     * Used in templates (*.phtml) files to get the most defined title in child
     * views.
     * 
     * @return string Child View title. 
     */
    public function getTitle() {
        return $this->_title;
    }

    /**
     * Define the View title.
     * 
     * @param string $title The View title.
     */
    public function setTitle($title) {
        $this->_title = $title;
    }

    /**
     * Used in templates (*.phtml) files to get the most defined description in child
     * views.
     * 
     * @return string Child View description.
     */
    public function getDescription() {
        return $this->_description? : $this->getTitle();
    }

    /**
     * Defined the View description.
     * 
     * @param string $description The View description.
     */
    public function setDescription($description) {
        $this->_description = $description;
    }

    /**
     * Used by Application to render the controller linked View.
     * 
     * @throws AcceleratorException If a specified parent View was not found.
     */
    public function render() {
        $currentView = $this;
        $this->_renderViews = new \SplStack();
        while ($currentView) {
            $this->_renderViews->push($currentView);
            $currentView = $currentView->getParentView();
        }

        $this->renderChild();
    }

    /**
     * Used in templates (*.phtml files) to display child content.
     * Each call to renderChild pop
     */
    public function renderChild() {
        include $this->_renderViews->pop()->_path;
    }

    public function addMeta($metaOrName, $content = null) {
        if ($metaOrName instanceof Html\Head\HeadMeta) {
            $meta = $metaOrName;
        } else if (is_string($metaOrName) && $content) {
            $meta = new Html\Head\HeadMeta($metaOrName, $content);
        }

        if ($meta) {
            if (!$this->_headMetas)
                $this->_headMetas = array();
            $this->_headMetas[] = $meta;
        }
    }

    public function addHeadLink($relOrLink, $href = null) {
        if ($relOrLink instanceof Html\Head\HeadLink) {
            $link = $relOrLink;
        } else if (is_string($relOrLink) && $href) {
            $link = new Html\Head\HeadLink($relOrLink, $href);
        }

        if ($link) {
            if (!$this->_headLinks)
                $this->_headLinks = array();
            $this->_headLinks[] = $link;
        }
    }

    public function addHeadScript($srcOrScript) {
        if ($srcOrScript instanceof Html\Head\ScriptLink) {
            $script = $srcOrScript;
        } else if (is_string($srcOrScript)) {
            $script = new Html\Head\ScriptLink($srcOrScript);
        }

        if ($script) {
            if (!$this->_headScripts)
                $this->_headScripts = array();
            $this->_headScripts[] = $script;
        }
    }

    public function getMetas() {
        if ($this->_headMetas) {
            return join('', $this->_headMetas);
        }
    }

    public function getHeadLinks() {
        if ($this->_headLinks) {
            return join('', $this->_headLinks);
        }
    }

    public function getHeadScripts() {
        if ($this->_headScripts) {
            return join('', $this->_headScripts);
        }
    }

}

?>

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

    /**
     * Add a meta element to head part of this view.
     * 
     * @param \Accelerator\View\HeadMeta $metaOrName
     * @param string $content Javascript code content if needed.
     */
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

    /**
     * Add a link element to head part of this view.
     * 
     * @param \Accelerator\View\HeadLink $relOrLink
     * @param string $href
     */
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

    /**
     * Add a script element to head part of this view.
     * 
     * @param \Accelerator\View\Script $srcOrScript
     * @param string $jsCode
     */
    public function addHeadScript($srcOrScript, $jsCode = null) {
        if ($srcOrScript instanceof Html\Script) {
            $script = $srcOrScript;
        } else if (is_string($srcOrScript)) {
            $script = new Html\Script($srcOrScript);
        }

        if ($script) {
            if ($jsCode) {
                $script->setInnerHtml($jsCode);
            }
            if (!$this->_headScripts)
                $this->_headScripts = array();
            $this->_headScripts[] = $script;
        }
    }

    /**
     * Get the HTML of meta tags in head part of this view.
     * 
     * @return string
     */
    public function getMetas() {
        if (!$this->_headMetas)
            $this->_headMetas = array();

        $initMetas = $this->initMetas();
        array_merge($this->_headMetas, $initMetas);

        return join('', $this->_headMetas);
    }

    /**
     * Get the HTML of link tags in head part of this view.
     * 
     * @return string
     */
    public function getHeadLinks() {
        if (!$this->_headLinks)
            $this->_headLinks = array();

        $initLinks = $this->initHeadLinks();
        array_merge($this->_headLinks, $initLinks);

        return join('', $this->_headLinks);
    }

    /**
     * Get the HTML of script tags in head part of this view.
     * 
     * @return string
     */
    public function getHeadScripts() {
        if (!$this->_headScripts)
            $this->_headScripts = array();

        $initScripts = $this->initHeadScripts();
        array_merge($this->_headScripts, $initScripts);

        return join('', $this->_headScripts);
    }

    /**
     * 
     * @return array of \Accelerator\View\Html\Head\HeadLink
     */
    protected function initHeadLinks() {
        return null;
    }

    /**
     * 
     * @return array of \Accelerator\View\Html\Head\HeadMeta
     */
    protected function initMetas() {
        return null;
    }

    /**
     * 
     * @return array of \Accelerator\View\Html\Script
     */
    protected function initHeadScripts() {
        return null;
    }

    /**
     * Returns the current request url.
     * 
     * @return string
     */
    public function getUrl($includeBaseUrl = false) {
        return ($includeBaseUrl ? $this->getApplication()->getBaseUrl() : '') . $_SERVER['REQUEST_URI'];
    }

}

?>

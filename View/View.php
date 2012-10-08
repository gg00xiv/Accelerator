<?php

namespace EasyMvc\View;

use EasyMvc\EasyMvcException;
use EasyMvc\Application;

/**
 * Description of View
 *
 * @author gg00xiv
 */
class View {

    private $path;
    private $childView;
    private $parentViewName;
    private $title;

    public function __construct($path, $parentViewName = null) {
        if (!is_string($path) || empty($path))
            throw new EasyMvcException('Invalid parameters.');

        $this->path = $path;
        if (is_string($parentViewName))
            $this->parentViewName = $parentViewName;
    }

    public function getTitle() {
        if ($this->childView)
            return $this->childView->getTitle();
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getPath() {
        return $this->path;
    }

    public function render() {
        if ($this->parentViewName) {
            $parentView = Application::instance()->getView($this->parentViewName);
            if (!$parentView)
                throw new EasyMvcException('Parent view not found : ' . $this->parentViewName);
            $parentView->childView = $this;
            $parentView->render();
        }
        else
            $this->renderSelf();
    }

    private function renderSelf() {
        include $this->path;
    }

    public function renderChild() {
        if ($this->childView)
            $this->childView->renderSelf();
    }

}

?>

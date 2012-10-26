<?php

namespace Accelerator\Script;

/**
 * Description of JsBuilder
 *
 * @author gg00xiv
 */
class JsBuilder {

    private $js = '';

    private static function getElementById($id) {
        return 'document.getElementById(\'' . $id . '\')';
    }

    private static function getLiteralFromValue($value) {
        return is_string($value) ? "'$value'" : $value;
    }

    public function createVar($name, $value) {
        $this->js.='var ' . $name . '=' . self::getLiteralFromValue($value) . ';';
        return $this;
    }

    public function createVarFromElementId($name, $id) {
        $this->js.='var ' . $name . '=' . self::getElementById($id) . ';';
        return $this;
    }

    public function elementId($id) {
        $this->js.=self::getElementById($id);
        return $this;
    }

    public function hideElementId($id) {
        $this->js.=self::getElementById($id) . '.style.display=\'none\';';
        return $this;
    }

    public function showElementId($id) {
        $this->js.=self::getElementById($id) . '.style.display=\'block\';';
        return $this;
    }

    public function switchVisibilityElementId($id) {
        $this->js.='if (' . self::getElementById($id) . '.style.display==\'block\') ';
        $this->hideElementId($id);
        $this->js .=' else ';
        return $this->showElementId($id);
    }

    public function returnValue() {
        $this->js.='return ';
        return $this;
    }

    public function confirm($message) {
        $this->js.='confirm(\'' . $message . '\');';
        return $this;
    }

    public function getScript() {
        return $this->js;
    }

    public function getInlineScript() {
        $script = new \Accelerator\View\Html\Script();
        $script->setInnerHtml($this->getScript());
        return $script;
    }

    public function __toString() {
        return $this->getScript();
    }

}

?>

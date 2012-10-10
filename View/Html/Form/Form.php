<?php

namespace Accelerator\View\Html\Form;

use Accelerator\View\Html\HtmlElement;

/**
 * Description of Form
 *
 * @author gg00xiv
 */
class Form extends HtmlElement {

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    public function __construct(array $attributes = array()) {
        parent::__construct('form', $attributes);
    }

    public function setMethod($method) {
        if (!in_array($method, array(self::METHOD_GET, self::METHOD_POST))) {
            throw new \Accelerator\AcceleratorException('Invalid method : ' . $method);
        }

        $this->attributes['method'] = $method;
    }

    public function getMethod() {
        return $this->attributes['method'];
    }

    public function getValue($name) {
        foreach ($this->elements as $element) {
            if (!$element instanceof FormElement)
                continue;
            if ($element->getName() == $name)
                return $element->getValue();
        }
        throw new \Accelerator\AcceleratorException('Value not found for : ' . $name);
    }

    public function getValues() {
        $values = array();
        foreach ($this->elements as $element) {
            if (!$element instanceof FormElement)
                continue;
            switch ($this->getMethod()) {
                case self::METHOD_GET:
                    $values[$element->getName()] = $_GET[$element->getName()];
                    break;
                case self::METHOD_POST:
                    $values[$element->getName()] = $_POST[$element->getName()];
                    break;
            }
        }
        return $values;
    }

    public function __toString() {
        ob_start();
        parent::render();
        return ob_get_clean();
    }

}

?>

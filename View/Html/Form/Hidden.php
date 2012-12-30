<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of Hidden
 *
 * @author gg00xiv
 */
class Hidden extends FormElement {

    public function __construct($name, $value = null, array $attributes = null) {
        if (!$name)
            throw new \Accelerator\Exception\ArgumentNullException('$name');

        parent::__construct('input', array_merge(array('type' => 'hidden', 'name' => $name, 'value' => $value), $attributes? : array()));
    }

    protected function onSetValue($value) {
        $this->attributes['value'] = $value;
    }

}

?>
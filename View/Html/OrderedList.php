<?php

namespace Accelerator\View\Html;

/**
 * Description of OrderedList
 *
 * @author gg00xiv
 */
class OrderedList extends AbstractList {

    public function __construct(array $attributes = null) {
        parent::__construct('ol', $attributes);
    }

}

?>

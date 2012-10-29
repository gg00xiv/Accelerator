<?php

namespace Accelerator\View\Html;

/**
 * Description of UnorderedList
 *
 * @author gg00xiv
 */
class UnorderedList extends AbstractList {

    public function __construct(array $list = null, array $attributes = null) {
        parent::__construct('ul', $attributes, $list);
    }

}

?>

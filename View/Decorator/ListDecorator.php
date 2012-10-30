<?php

namespace Accelerator\View\Decorator;

/**
 * Description of ListDecorator
 *
 * @author gg00xiv
 */
class ListDecorator extends \Accelerator\Stdlib\Decorator {

    protected function getTemplate($item) {
        return $item;
    }

    public function getList($startTag = null, $endTag = null) {
        $list = $startTag? : '';
        foreach ($this->innerObj as $item)
            $list.=$this->getTemplate($item);
        $list.=$endTag;
        return $list;
    }

    public function __toString() {
        return $this->getList();
    }

}

?>

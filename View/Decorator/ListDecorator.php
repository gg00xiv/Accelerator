<?php

namespace Accelerator\View\Decorator;

/**
 * Description of ListDecorator
 *
 * @author gg00xiv
 */
abstract class ListDecorator extends \Accelerator\Stdlib\Decorator {

    public function __construct($list) {
        /*if (!is_array($list) && !$list instanceof Traversable)
            throw new \Accelerator\Exception\ArgumentException('$list', 'Must be Traversable or array instance.');*/
        
        parent::__construct($list);
    }

    protected abstract function getTemplate($item);

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

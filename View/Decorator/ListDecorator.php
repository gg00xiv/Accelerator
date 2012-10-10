<?php

namespace Accelerator\View\Decorator;

/**
 * Description of ListDecorator
 *
 * @author gg00xiv
 */
abstract class ListDecorator {

    private $list;

    public function __construct(array $list) {
        $this->list = $list;
    }

    protected abstract function getTemplate($item);

    public function getList($startTag = null, $endTag = null) {
        $list = $startTag? : '';
        foreach ($this->list as $item)
            $list.=$this->getTemplate($item);
        $list.=$endTag;
        return $list;
    }

}

?>

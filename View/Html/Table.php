<?php

namespace Accelerator\View\Html;

/**
 * Description of Table
 *
 * @author gg00xiv
 */
class Table extends HtmlElement {

    protected $mustCloseTag = true;

    public function __construct(array $attributes = null) {
        parent::__construct('table', $attributes);
    }

    public function insertElement($position, HtmlElement $element) {
        if (!$element instanceof TableRow && !$element instanceof TableHead && !$element instanceof TableBody && !$element instanceof TableFoot)
            throw new Exception\HtmlException('$element must be an instance of TableRow, TableHead, TableBody or TableFoot.');

        foreach ($this->getElements() as $elt) {
            if ($element instanceof TableHead && $elt instanceof TableHead)
                throw new Exception\HtmlException('Cannot add more than once TableHead to a Table object.');
            if ($element instanceof TableBody && $elt instanceof TableBody)
                throw new Exception\HtmlException('Cannot add more than once TableBody to a Table object.');
            if ($element instanceof TableFoot && $elt instanceof TableFoot)
                throw new Exception\HtmlException('Cannot add more than once TableFoot to a Table object.');
        }
        parent::insertElement($position, $element);
    }

    /**
     * @return Accelerator\View\Html\TableBody
     */
    private function getBody() {
        if (!isset($this->_body)) {
            foreach ($this->getElements() as $element) {
                if ($element instanceof TableBody) {
                    $this->_body = $element;
                    break;
                }
            }
            if (!$this->_body) {
                $this->_body = new TableBody ();
                $this->addElement($this->_body);
            }
        }

        return $this->_body;
    }

    /**
     * 
     * @return Accelerator\View\Html\TableHead
     */
    private function getHead() {
        if (!isset($this->_head)) {
            foreach ($this->getElements() as $element) {
                if ($element instanceof TableHead) {
                    $this->_head = $element;
                    break;
                }
            }
            if (!$this->_head) {
                $this->_head = new TableHead ();
                $this->addElement($this->_head);
            }
        }

        return $this->_head;
    }

    /**
     * Simply define TableHead with column names for this Table instance.
     * 
     * @param array $columnNames
     * @throws \Accelerator\Exception\ArgumentNullException
     */
    public function setColumns(array $columnNames) {
        if (!$columnNames)
            throw new \Accelerator\Exception\ArgumentNullException('$columnNames');

        $this->getHead()
                ->clearElements()
                ->addElement($row = new TableRow());
        foreach ($columnNames as $columnName)
            $row->addElement(new TableHeader($columnName));
    }

    /**
     * Add a row to the TableBody part of this Table instance.
     * 
     * @param mixed $rowOrArray TableRow or array types accepted.
     * @throws \Accelerator\Exception\ArgumentException
     */
    public function addRow($rowOrArray) {
        if ($rowOrArray instanceof TableRow) {
            $row = $rowOrArray;
        } else if (is_array($rowOrArray)) {
            $row = new TableRow($rowOrArray);
        } else {
            throw new \Accelerator\Exception\ArgumentException('$rowOrArray', 'Must be an instance of array or TableRow.');
        }

        $this->getBody()->addElement($row);
        return $row;
    }

}

?>

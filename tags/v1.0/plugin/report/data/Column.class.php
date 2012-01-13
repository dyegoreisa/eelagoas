<?php

class Column 
{
    /**
     * Informa se tem fundo transparente
     * 
     * @var int
     * @access private
     */
    private $fill;
    private $field;
    private $text;
    private $align;
    private $width;
    private $height;
    private $columns;
    private $id;

    public function setFill($fill)
    {
        $this->fill = $fill;
    }

    public function getFill()
    {
        return $this->fill;
    }

    public function setField($field)
    {
        $this->field = $field;
    }

    public function getField()
    {
        return $this->field;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setAlign($align)
    {
        $this->align = $align;
    }

    public function getAlign()
    {
        return $this->align;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getWidth()
    {
        return $this->width;
    }
    
    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setRecursiveWidthByColumns()
    {
        $width = 0;
        $count = 0;
        if (is_array($this->getColumns())) {
            foreach ($this->getColumns() as $coluna) {
                $width += $coluna->setRecursiveWidthByColumns();
                if (!is_array($coluna->getColumns())) {
                    $count++;
                }
            }
            $this->setWidth($count + $width);
        } else {
            $this->setWidth(0);
        }

        return $this->getWidth();
    }
}

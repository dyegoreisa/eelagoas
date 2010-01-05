<?php

class Column 
{
    private $field;
    private $text;
    private $align;

    /**
     * Informa se tem fundo transparente
     * 
     * @var int
     * @access private
     */
    private $fill;
    private $width;

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

    public function setFill($fill)
    {
        $this->fill = $fill;
    }

    public function getFill()
    {
        return $this->fill;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getWidth()
    {
        return $this->width;
    }
    
}

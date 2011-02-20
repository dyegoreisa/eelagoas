<?php
class Ordem
{
    private $id;
    private $field;
    
    // Implementado somente para o render PDF
    private $width;
    private $height;
    private $text;
    
    public function __construct($id, $field, $width = null, $height = null, $text = null)
    {
        $this->id    = $id;
        $this->field = $field;

        // Implementado somente para o render PDF
        $this->width  = $width;
        $this->height = $height;
        $this->text   = $text;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function getText()
    {
        return $this->text;
    }

    public function is($id, $field)
    {
        return ($this->field == $field && $this->id == $id) ? true : false;
    }
}

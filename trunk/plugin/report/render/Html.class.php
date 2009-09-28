<?php
class Html extends Render
{
    private $formatedData;

    public function __construct() 
    {
        parent::__construct();
    }
    public function getDisplaySearch()
    {
    }

    public function render()
    {
        $this->data = $this->result->execute();
        debug($this->data);
    }
}

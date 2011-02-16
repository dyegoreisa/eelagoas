<?php
class Ordem
{
	private $id;
	private $field;
	
	public function __construct($id, $field)
	{
		$this->id    = $id;
		$this->field = $field;
	}

    public function getId()
    {
        return $this->id;
    }

    public function getField()
    {
        return $this->field;
    }

    public function is($id, $field)
    {
        return ($this->field == $field && $this->id == $id) ? true : false;
    }
}

<?php
class Perfil extends BaseModel 
{
    private $listaAssoc;

    public function __construct( $dbh ) 
    {
        parent::__construct( $dbh );

        $this->table    = 'perfil';
        $this->nameId   = 'id_perfil';
        $this->nameDesc = 'nome';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
            'id_perfil'   => '=',
            'nome'         => 'LIKE'
        );
    }
}
?>

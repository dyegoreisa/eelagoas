<?php
class Acao extends BaseModel 
{
    private $listaAssoc;

    public function __construct( $dbh ) 
    {
        parent::__construct( $dbh );

        $this->table    = 'acao';
        $this->nameId   = 'id_acao';
        $this->nameDesc = 'nome';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
            'id_acao'   => '=',
            'nome'      => '=',
            'modulo'    => '=',
            'acesso'    => '='
        );
    }
}
?>

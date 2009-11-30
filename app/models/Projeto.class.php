<?php
class Projeto extends BaseModel {
    public function __construct( $dbh ) {
        parent::__construct( $dbh );

        $this->table    = 'projeto';
        $this->nameId   = 'id_projeto';
        $this->nameDesc = 'nome';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
            'id_projeto' => '=',
            'nome'     => 'LIKE'
        );
    }
}

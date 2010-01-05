<?php
class Categoria extends BaseModel {
  public function __construct( $dbh ) {
    parent::__construct( $dbh );

    $this->table    = 'categoria';
    $this->nameId   = 'id_categoria';
    $this->nameDesc = 'nome';
    $this->data     = array();
    $this->dataAll  = array();
    $this->search   = array(
      'id_categoria'  => '=',
      'nome'      => 'LIKE'
    );
  }
}

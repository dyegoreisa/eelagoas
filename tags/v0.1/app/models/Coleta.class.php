<?php
class Coleta extends BaseModel {
  public function __construct( $dbh ) {
    parent::__construct( $dbh );

    $this->table    = 'coleta';
    $this->nameId   = 'id_coleta';
    $this->data     = array();
    $this->dataAll  = array();
    $this->search   = array(
      'id_coleta'         => '=',
      'id_lagoa'          => '=',
      'id_ponto_amostral' => '=',
      'id_categoria'      => '=',
      'data'              => 'LIKE'
    );
  }
}

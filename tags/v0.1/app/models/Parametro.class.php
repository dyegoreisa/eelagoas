<?php
class Parametro extends BaseModel {
  public function __construct( $dbh ) {
    parent::__construct( $dbh );

    $this->table    = 'parametro';
    $this->nameId   = 'id_parametro';
    $this->data     = array();
    $this->dataAll  = array();
    $this->search   = array(
      'id_parametro'  => '=',
      'nome'      => 'LIKE'
    );
  }

  public function listarSelectAssoc() {
    $sth = $this->dbh->prepare("
      SELECT 
        id_parametro 
        , nome 
      FROM 
        parametro 
    ");

    $sth->execute();
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $lista = $sth->fetchAll();

    foreach( $lista as $item ) {
      $lista2[$item['id_parametro']] = $item;
    }

    return @$lista2;
  }
}

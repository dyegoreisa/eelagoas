<?php
class Categoria extends BaseModel {
  public function __construct( $dbh ) {
    parent::__construct( $dbh );

    $this->table    = 'categoria';
    $this->nameId   = 'id_categoria';
    $this->data     = array();
    $this->dataAll  = array();
    $this->search   = array(
      'id_categoria'  => '=',
      'nome'      => 'LIKE'
    );
  }

  public function listarSelectAssoc() {
    $sth = $this->dbh->prepare("
      SELECT 
        id_categoria 
        , nome 
      FROM 
        categoria 
    ");

    $sth->execute();
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $lista = $sth->fetchAll();

    $lista2 = array();
    foreach( $lista as $item ) {
      $lista2[$item['id_categoria']] = $item['nome'];
    }

    return $lista2;
  }
}

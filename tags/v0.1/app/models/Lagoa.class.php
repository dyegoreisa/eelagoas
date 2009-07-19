<?php
class Lagoa extends BaseModel {
  public function __construct( $dbh ) {
    parent::__construct( $dbh );

    $this->table    = 'lagoa';
    $this->nameId   = 'id_lagoa';
    $this->data     = array();
    $this->dataAll  = array();
    $this->search   = array(
      'id_lagoa'  => '=',
      'nome'      => 'LIKE'
    );
  }

  public function listarSelectAssoc() {
    
    $sth = $this->dbh->prepare("
      SELECT 
        id_lagoa 
        , nome 
      FROM 
        lagoa 
    ");

    $sth->execute();
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $lista = $sth->fetchAll();

    foreach( $lista as $item ) {
      $lista2[$item['id_lagoa']] = $item['nome'];
    }

    return @$lista2;
  }
}

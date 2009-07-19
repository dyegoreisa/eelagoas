<?php
class PontoAmotral extends BaseModel {
  public function __construct( $dbh ) {
    parent::__construct( $dbh );

    $this->table    = 'ponto_amostral';
    $this->nameId   = 'id_ponto_amostral';
    $this->data     = array();
    $this->dataAll  = array();
    $this->search   = array(
      'id_ponto_amostral' => '=',
      'id_lagoa'          => '=',
      'nome'              => 'LIKE'
    );
  }

  public function listarSelectAssoc( $id_lagoa ) {
    $sth = $this->dbh->prepare("
      SELECT 
        id_ponto_amostral 
        , nome 
      FROM 
        ponto_amostral 
      WHERE
        id_lagoa = :id_lagoa
    ");

    $sth->execute( array( ':id_lagoa' => $id_lagoa ) );
    $sth->setFetchMode(PDO::FETCH_ASSOC);
    $lista = $sth->fetchAll();

    foreach( $lista as $item ) {
      $lista2[$item['id_ponto_amostral']] = $item['nome'];
    }

    return @$lista2;
  }
}

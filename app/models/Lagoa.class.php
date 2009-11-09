<?php
class Lagoa extends BaseModel {
    public function __construct( $dbh ) {
        parent::__construct( $dbh );

        $this->table   = 'lagoa';
        $this->nameId  = 'id_lagoa';
        $this->data    = array();
        $this->dataAll = array();
        $this->search  = array(
            'id_lagoa' => '=',
            'nome'     => 'LIKE'
        );
    }

    public function listarSelectAssoc( $idProjeto ) {
        
        $sth = $this->dbh->prepare("
            SELECT 
                id_lagoa 
                , nome 
            FROM 
                lagoa 
            WHERE
                id_projeto = $idProjeto
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $lista = $sth->fetchAll();

        $lista2 = array();
        foreach( $lista as $item ) {
            $lista2[$item['id_lagoa']] = $item['nome'];
        }

        return $lista2;
    }
}

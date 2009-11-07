<?php
class Projeto extends BaseModel {
    public function __construct( $dbh ) {
        parent::__construct( $dbh );

        $this->table   = 'projeto';
        $this->nameId  = 'id_projeto';
        $this->data    = array();
        $this->dataAll = array();
        $this->search  = array(
            'id_projeto' => '=',
            'nome'     => 'LIKE'
        );
    }

    public function listarSelectAssoc() {
        
        $sth = $this->dbh->prepare("
            SELECT 
                id_projeto 
                , nome 
            FROM 
                projeto 
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $lista = $sth->fetchAll();

        $lista2 = array();
        foreach( $lista as $item ) {
            $lista2[$item['id_projeto']] = $item['nome'];
        }

        return $lista2;
    }
}

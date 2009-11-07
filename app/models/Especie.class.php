<?php
class Especie extends BaseModel 
{
    private $listaAssoc;

    public function __construct( $dbh ) 
    {
        parent::__construct( $dbh );

        $this->table   = 'especie';
        $this->nameId  = 'id_especie';
        $this->data    = array();
        $this->dataAll = array();
        $this->search  = array(
            'id_especie'   => '=',
            'id_parametro' => '=',
            'nome'         => 'LIKE'
        );

        $this->listarAssoc();
    }

    public function listarAssoc() 
    {
        $sth = $this->dbh->prepare("
            SELECT 
                id_especie 
                , nome 
            FROM 
                especie 
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $this->listaAssoc = $sth->fetchAll();
    }

    public function listarSelectAssoc() 
    {
        $lista2 = array();
        foreach($this->listaAssoc as $item ) {
            $lista2[$item['id_especie']] = $item['nome'];
        }
        return $lista2;
    }
}

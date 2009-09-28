<?php
class Parametro extends BaseModel 
{
    private $listaAssoc;

    public function __construct( $dbh ) 
    {
        parent::__construct( $dbh );

        $this->table   = 'parametro';
        $this->nameId  = 'id_parametro';
        $this->data    = array();
        $this->dataAll = array();
        $this->search  = array(
            'id_parametro' => '=',
            'nome'         => 'LIKE'
        );

        $this->listarAssoc();
    }

    public function listarAssoc() 
    {
        $sth = $this->dbh->prepare("
            SELECT 
                id_parametro 
                , nome 
            FROM 
                parametro 
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $this->listaAssoc = $sth->fetchAll();
    }

    public function listarSelectAssoc() 
    {
        $lista2 = array();
        foreach($this->listaAssoc as $item ) {
            $lista2[$item['id_parametro']] = $item['nome'];
        }
        return $lista2;
    }

    public function listarCheckboxAssoc()
    {
        $lista2 = array();
        foreach($this->listaAssoc as $item ) {
            $lista2[$item['id_parametro']] = $item;
        }
        return $lista2;
    }
}

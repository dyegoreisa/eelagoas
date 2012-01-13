<?php
class Parametro extends BaseModel 
{
    private $listaAssoc;

    public function __construct( $dbh ) 
    {
        parent::__construct( $dbh );

        $this->table    = 'parametro';
        $this->nameId   = 'id_parametro';
        $this->nameDesc = 'nome';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
            'id_parametro' => '=',
            'nome'         => 'LIKE'
        );

        $this->listarAssoc();
    }

    public function listarAssoc() 
    {
        $sth = $this->dbh->prepare("
            SELECT 
                p.id_parametro 
                , p.nome 
                , p.composicao
            FROM parametro p
            ORDER BY p.nome
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $this->listaAssoc = $sth->fetchAll();
    }

    public function listarCheckBoxAssoc()
    {
        $lista2 = array();
        foreach($this->listaAssoc as $item ) {
            $lista2[$item['id_parametro']] = $item;
        }
        return $lista2;
    }

    public function listarComposicoes()
    {
        $sth = $this->dbh->prepare("
            SELECT 
                p.id_parametro 
                , p.nome 
            FROM parametro p
            WHERE p.composicao is true
            ORDER BY p.nome
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $this->assocArray($sth->fetchAll(), 'id_parametro', 'nome');
    }

    public function eComposicao() {
        $sth = $this->dbh->prepare("
            SELECT p.composicao
            FROM parametro p
            WHERE p.id_parametro = :id
            LIMIT 1
        ");

        $sth->execute(array(':id' => $this->getId()));
        $profundidade = $sth->fetch();
        return ($profundidade['composicao'] == 1) ? true : false;
    }
}

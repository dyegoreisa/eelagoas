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
                , e.id_parametro_extra
                , e.nome as nome_campo_extra
                , e.descricao
                , e.tem_valor
                , e.tem_relacao
            FROM parametro p
            JOIN parametro_extra e ON e.id_parametro_extra = p.id_parametro_extra
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

    public function listarSelectAssocExtra()
    {
        $sth = $this->dbh->prepare("
            SELECT 
                p.id_parametro 
                , p.nome 
            FROM parametro p
            JOIN parametro_extra e ON e.id_parametro_extra = p.id_parametro_extra
            WHERE e.tem_relacao is true
            ORDER BY p.nome
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $this->assocArray($sth->fetchAll(), 'id_parametro', 'nome');
    }
}

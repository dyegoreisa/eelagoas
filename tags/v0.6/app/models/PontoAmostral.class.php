<?php
class PontoAmotral extends BaseModel 
{
    public function __construct( $dbh ) {
        parent::__construct( $dbh );

        $this->table    = 'ponto_amostral';
        $this->nameId   = 'id_ponto_amostral';
        $this->nameDesc = 'nome';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
            'id_ponto_amostral' => '=',
            'id_lagoa'          => '=',
            'nome'              => 'LIKE'
        );
    }

    /**
     * TODO: Implementar o recebimento de parametro com array para fazer um implode e colocar no IN
     */
    public function listarSelectAssoc($lagoas) {
        $sth = $this->dbh->prepare("
            SELECT 
                id_ponto_amostral 
                , nome 
            FROM 
                ponto_amostral 
            WHERE
                id_lagoa IN ($lagoas)
            ORDER BY nome
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $this->assocArray($sth->fetchAll(), 'id_ponto_amostral', 'nome');
    }

    public function listar($order = false) {
        $sqlOrder = '';
        if ($order) {
            $sqlOrder = " ORDER BY {$order['campo']} {$order['ordem']}";
        }
        $sth = $this->dbh->prepare("
            SELECT 
                pa.id_ponto_amostral 
                , pa.nome
                , l.id_lagoa
                , l.nome as nome_lagoa
            FROM
                ponto_amostral pa
                JOIN lagoa l ON l.id_lagoa = pa.id_lagoa
            $sqlOrder
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $lista = $sth->fetchAll();

        return $lista;
    }
}

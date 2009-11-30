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
        $lista = $sth->fetchAll();
        $lista2 = array();
        foreach( $lista as $item ) {
            $lista2[$item['id_ponto_amostral']] = $item['nome'];
        }

        return $lista2;
    }
}

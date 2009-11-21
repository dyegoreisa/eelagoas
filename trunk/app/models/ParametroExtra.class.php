<?php
class ParametroExtra extends BaseModel {
    public function __construct( $dbh ) {
        parent::__construct( $dbh );

        $this->table    = 'parametro_extra';
        $this->nameId   = 'id_parametro_extra';
        $this->nameDesc = 'nome';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
            'id_parametro_extra' => '=',
            'nome'     => 'LIKE'
        );
    }

    public function getCampoExtraByIdExtra($idExtra) {
        $sth = $this->dbh->prepare('
            SELECT
                ce.id_parametro_extra
                , ce.nome              
                , ce.descricao         
                , ce.tem_valor         
                , ce.tem_relacao       
                , ce.tabela
            FROM
                parametro_extra ce 
            WHERE
                ce.id_parametro_extra = :idExtra
        ');

        $sth->execute(array(':idExtra' => $idExtra));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $sth->fetch();
    }
}

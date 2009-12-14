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
            'id_parametro'       => '=',
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

    public function temExtra($parametros) {
        if (is_array($parametros)) {
            $listaParametros = implode(',', $parametros);
        } else {
            $listaParametros = $parametros;
        }

        $sth = $this->dbh->prepare("
            SELECT
                MAX(pe.tem_relacao) AS tem_relacao
            FROM
                parametro p 
                    JOIN parametro_extra pe ON pe.id_parametro_extra = p.id_parametro_extra 
            WHERE
                p.id_parametro IN ($listaParametros)
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $rs = $sth->fetch();
        return ($rs['tem_relacao'] == 1) ? true : false;
    }
}

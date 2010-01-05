<?php
class ColetaParametro extends BaseModel {
    public function __construct( $dbh ) {
        parent::__construct( $dbh );

        $this->table   = 'coleta_parametro';
        $this->nameId  = 'id_coleta_parametro';
        $this->data    = array();
        $this->dataAll = array();
        $this->search  = array(
            'id_coleta_parametro' => '=',
            'id_coleta'           => '=',
            'extra'               => '=',
            'valor'               => 'LIKE'
        );
    }

    public function listarSelectAssoc( $id ) {
        $sth = $this->dbh->prepare("
            select
                p.id_parametro
                , p.nome
                , cp.id_coleta_parametro
                , cp.valor
                , cp.valor_extra
                , e.id_parametro_extra
                , e.nome as nome_campo_extra
                , e.descricao
                , e.tem_valor
                , e.tem_relacao
            from 
                parametro p 
                join parametro_extra e on e.id_parametro_extra = p.id_parametro_extra
                left join coleta_parametro cp on cp.id_parametro = p.id_parametro 
                and cp.id_coleta = :id_coleta
            order by p.nome
        ");

        $sth->execute( array( ':id_coleta' => $id ) );
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $lista = $sth->fetchAll();

        $lista2 = array();
        foreach( $lista as $item ) {
            $lista2[$item['id_parametro']] = $item;
        }

        return $lista2;
    }

    public function excluir( $idColeta ){
        $sth = $this->dbh->prepare('DELETE FROM coleta_parametro WHERE id_coleta = ?');
        $sth->execute(array($idColeta));
        if( $this->dbh->errorCode() != 00000 ) {
            $e = $this->dbh->errorInfo();
            throw new Exception( "<hr>{$e[2]}<hr>" );
        }
    }
}

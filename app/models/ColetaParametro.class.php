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
            'nivel'               => '=',
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
                , cp.nivel
            from 
                parametro p 
                left join coleta_parametro cp on cp.id_parametro = p.id_parametro 
                and cp.id_coleta = :id_coleta
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
        $sql = "DELETE FROM coleta_parametro WHERE id_coleta = {$idColeta}";
        $ok = $this->dbh->exec( $sql );
        if( $this->dbh->errorCode() != 00000 ) {
            $e = $this->dbh->errorInfo();
            throw new Exception( "<hr>{$e[2]}<br>SQL: {$sql}<hr>" );
        }

        return ($ok) ? true : false;
    }
}

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
            SELECT
                p.id_parametro
                , p.nome 
                , cp.id_coleta_parametro
                , cp.valor 
                , p.composicao 
            FROM parametro p 
                LEFT JOIN coleta_parametro cp ON cp.id_parametro = p.id_parametro 
                    AND cp.id_coleta = :id_coleta
            ORDER BY p.nome
        ");

        $sth->execute(array(':id_coleta' => $id));
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

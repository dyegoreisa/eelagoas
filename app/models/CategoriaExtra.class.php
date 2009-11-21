<?php
class CategoriaExtra extends BaseModel {
    public function __construct( $dbh ) {
        parent::__construct( $dbh );

        $this->table    = 'categoria_extra';
        $this->nameId   = 'id_categoria_extra';
        $this->nameDesc = 'nome';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
            'id_categoria_extra' => '=',
            'nome'     => 'LIKE'
        );
    }

    public function getCampoExtraByIdCategoria($idCategoria) {
        $sth = $this->dbh->prepare('
            SELECT
                ce.id_categoria_extra
                , ce.nome              
                , ce.descricao         
                , ce.tem_valor         
                , ce.tem_relacao       
                , ce.tabela
            FROM
                categoria c 
                    JOIN categoria_extra ce 
                    ON ce.id_categoria_extra = c.id_categoria_extra 
            WHERE
                c.id_categoria = :idCategoria
        ');

        $sth->execute(array(':idCategoria' => $idCategoria));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $sth->fetch();
    }

    public function getCampoExtraByIdExtra($idExtra) {
        $sth = $this->dbh->prepare('
            SELECT
                ce.id_categoria_extra
                , ce.nome              
                , ce.descricao         
                , ce.tem_valor         
                , ce.tem_relacao       
                , ce.tabela
            FROM
                categoria_extra ce 
            WHERE
                ce.id_categoria_extra = :idExtra
        ');

        $sth->execute(array(':idExtra' => $idExtra));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $sth->fetch();
    }
}

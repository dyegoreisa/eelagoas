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

    public function getCampoExtraByIdColeta($idColeta) {
        $sth = $this->dbh->prepare('
            SELECT
                ce.id_categoria_extra
                , ce.nome              
                , ce.descricao         
                , ce.tem_valor         
                , ce.tem_relacao       
                , ce.tabela
                , cp.valor_categoria_extra
            FROM
                coleta c
                JOIN categoria ca ON ca.id_categoria = c.id_categoria
                JOIN categoria_extra ce ON ce.id_categoria_extra = ca.id_categoria_extra
                JOIN coleta_parametro cp ON cp.id_coleta = c.id_coleta
            WHERE
                c.id_coleta = :idColeta LIMIT 1
        ');

        $sth->execute(array(':idColeta' => $idColeta));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $sth->fetch();
    }

    public function temExtra($categorias) {
        if (is_array($categorias)) {
            $listaCategorias = implode(',', $categorias);
        } else {
            $listaCategorias = $categorias;
        }

        $sth = $this->dbh->prepare("
            SELECT
                MAX(ce.tem_valor) AS tem_valor
            FROM
                categoria ca 
                    JOIN categoria_extra ce ON ce.id_categoria_extra = ca.id_categoria_extra 
            WHERE
                ca.id_categoria IN ($listaCategorias)
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $rs = $sth->fetch();
        return ($rs['tem_valor'] == 1) ? true : false;
    }

    public function listarSelectAssocExtra($categorias, $lagoas = false) {
        if (is_array($categorias)) {
            $listaCategorias = implode(', ', $categorias);
        } else {
            $listaCategorias = $categorias;
        }

        if ($lagoas) {    
            if (is_array($lagoas)) {
                $clausLagoa = ' AND l.id_lagoa IN (' . implode(', ', $lagoas) . ') ';
            } else {
                $clausLagoa = " AND l.id_lagoa IN ({$lagoas}) ";
            }
        }
        
        $sth = $this->dbh->prepare("
            SELECT
                DISTINCT cp.valor_categoria_extra
            FROM
                coleta_parametro cp 
                JOIN coleta c ON c.id_coleta = cp.id_coleta
                JOIN lagoa l ON l.id_lagoa = c.id_lagoa
            WHERE
                valor_categoria_extra IS NOT NULL
                AND c.id_categoria IN ($listaCategorias)
                $clausLagoa
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $this->assocArray($sth->fetchAll(), 'valor_categoria_extra', 'valor_categoria_extra');
        
    }
}

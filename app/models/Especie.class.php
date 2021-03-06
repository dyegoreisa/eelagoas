<?php
class Especie extends BaseModel 
{
    private $listaAssoc;

    public function __construct( $dbh ) 
    {
        parent::__construct( $dbh );

        $this->table    = 'especie';
        $this->nameId   = 'id_especie';
        $this->nameDesc = 'nome';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
            'id_especie'   => '=',
            'id_parametro' => '=',
            'nome'         => 'LIKE'
        );

        $this->listarAssoc();
    }

    public function listarAssoc() 
    {
        $sth = $this->dbh->prepare("
            SELECT 
                id_especie 
                , nome 
            FROM 
                especie 
            ORDER BY nome
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $this->listaAssoc = $sth->fetchAll();
    }

    /*public function listarAssocPorParametro($idParametro, $idColeta) 
    {
        $sth = $this->dbh->prepare('
            SELECT
                e.id_especie 
                , e.nome 
                , IF(cp.id_coleta IS NOT NULL, cpe.id_especie, NULL) especie_coleta 
            FROM
                especie e 
                    LEFT JOIN coleta_parametro_especie cpe ON cpe.id_especie = e.id_especie 
                    LEFT JOIN coleta_parametro cp ON cp.id_coleta_parametro = cpe.id_coleta_parametro 
                        AND cp.id_coleta = :idColeta
            WHERE
                e.id_parametro = :idParametro
            ORDER BY e.nome
        ');

        $sth->execute(array(
            ':idColeta'    => $idColeta,
            ':idParametro' => $idParametro
        ));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $lista = $sth->fetchAll();

        $lista2 = array();
        $lista3 = array();
        foreach ($lista as $item) {
            $lista2[$item['id_especie']] = $item['nome'];
            $lista3[$item['id_especie']] = $item['especie_coleta'];
        }

        return array(
            'select_assoc' => $lista2,
            'selected'     => $lista3
        );
    }*/

    public function listarPorParametro($idParametro, array $ordem) 
    {
        $sth = $this->dbh->prepare("
            SELECT
                e.id_especie 
                , e.nome
                , p.id_parametro
                , p.nome AS nome_parametro
            FROM
                especie e 
                    JOIN parametro p ON p.id_parametro = e.id_parametro
            WHERE
                e.id_parametro = :idParametro
            ORDER BY {$ordem['campo']} {$ordem['ordem']}
        ");

        $sth->execute(array(':idParametro' => $idParametro));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $lista = $sth->fetchAll();
        return $lista;
    }

    public function listar($order = false)
    {
        $clausOrder = '';
        if( $order ) {
            $clausOrder = " ORDER BY {$order['campo']} {$order['ordem']} ";
        }

        $sth = $this->dbh->prepare("
            SELECT
                e.id_especie
                , e.nome
                , p.id_parametro
                , p.nome AS nome_parametro
            FROM especie e
                JOIN parametro p ON p.id_parametro = e.id_parametro
            $clausOrder
        ");

        $sth->execute();

        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $sth->fetchAll();
        
    }


    public function listarSelectAssoc($parametros, $order = false)
    {
        if (is_array($parametros)) {
            $listaParametros = implode(', ', $parametros);
        } else {
            $listaParametros = $parametros;
        }

        $clausOrder = '';
        if( $order ) {
            $clausOrder = " ORDER BY {$order['campo']} {$order['ordem']} ";
        }

        $sth = $this->dbh->prepare("
            SELECT
                e.id_especie
                , e.nome
            FROM especie e
            WHERE e.id_parametro IN ($listaParametros)
            $clausOrder
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $this->assocArray($sth->fetchAll(), 'id_especie', 'nome');
    }

    public function listarSelected($parametros, $idColeta = false, $order = false)
    {
        if (is_array($parametros)) {
            $listaParametros = implode(', ', $parametros);
        } else {
            $listaParametros = $parametros;
        }

        $clausOrder = '';
        if( $order ) {
            $clausOrder = " ORDER BY {$order['campo']} {$order['ordem']} ";
        }

        $sth = $this->dbh->prepare("
            SELECT
                e.id_especie
                , e.nome
                , j1.quantidade
            FROM especie e 
                LEFT JOIN (	SELECT
                                cpe.id_especie, cpe.quantidade
                            FROM coleta_parametro cp 
                                JOIN coleta_parametro_especie cpe ON cpe.id_coleta_parametro = cp.id_coleta_parametro 
                            WHERE cp.id_coleta = :id_coleta ) j1 ON j1.id_especie = e.id_especie 
            WHERE
                e.id_parametro IN ($listaParametros);
            $clausOrder
        ");

        $sth->execute(array('id_coleta' => $idColeta));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $sth->fetchAll();
    }
}

<?php
class Lagoa extends BaseModel {
    public function __construct( $dbh ) {
        parent::__construct( $dbh );

        $this->table    = 'lagoa';
        $this->nameId   = 'id_lagoa';
        $this->nameDesc = 'nome';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
            'id_lagoa' => '=',
            'l.nome'     => 'LIKE'
        );
    }

    public function listarSelectAssoc($idProjeto,  $order = false) {
        $sqlOrder = '';
        if ($order) {
            $sqlOrder = " ORDER BY {$order['campo']} {$order['ordem']}";
        }

        $sth = $this->dbh->prepare("
            SELECT 
                id_lagoa 
                , nome 
            FROM 
                lagoa 
            WHERE
                id_projeto IN ($idProjeto)
            $sqlOrder
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $this->assocArray($sth->fetchAll(), 'id_lagoa', 'nome');
    }

    public function listarSelectAssocSimple($order = false) {
        $sqlOrder = '';
        if ($order) {
            $sqlOrder = " ORDER BY {$order['campo']} {$order['ordem']}";
        }

        $sth = $this->dbh->prepare("
            SELECT 
                id_lagoa 
                , nome 
            FROM 
                lagoa 
            $sqlOrder
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $this->assocArray($sth->fetchAll(), 'id_lagoa', 'nome');
    }

    public function listar($order = false) {
        $sqlOrder = '';
        if ($order) {
            $sqlOrder = " ORDER BY {$order['campo']} {$order['ordem']}";
        }
        $sth = $this->dbh->prepare("
            SELECT 
                l.id_lagoa 
                , l.nome
                , p.id_projeto
                , p.nome as nome_projeto
            FROM 
                lagoa l
                JOIN projeto p ON p.id_projeto = l.id_projeto
            $sqlOrder
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $lista = $sth->fetchAll();

        return $lista;
    }

    public function listarPorProjeto($idProjeto, $order = false) {
        $sqlOrder = '';
        if ($order) {
            $sqlOrder = " ORDER BY {$order['campo']} {$order['ordem']}";
        }
        $sth = $this->dbh->prepare("
            SELECT 
                l.id_lagoa 
                , l.nome
                , p.id_projeto
                , p.nome as nome_projeto
            FROM 
                lagoa l
                JOIN projeto p ON p.id_projeto = l.id_projeto
            WHERE
                p.id_projeto = :idProjeto
            $sqlOrder
        ");

        $sth->execute(array(':idProjeto' => $idProjeto));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $lista = $sth->fetchAll();

        return $lista;
    }

    protected function montarBusca( $dados, $campos ) {

        $this->prepararCampos($campos);
        $where = array();
        foreach( $this->search as $campo => $operador ) {
            $where[] = $this->montarParteBusca($campo, $operador, $dados);
        }

        $clausula = implode(' OR ', $where);

        return "
            SELECT 
                l.id_lagoa
                , l.nome
                , p.id_projeto
                , p.nome AS nome_projeto
            FROM lagoa l
            JOIN projeto p ON l.id_projeto = p.id_projeto
            WHERE $clausula
        ";
    }

    public function listarSelectAssocData($campo, $tipoPeriodo, $idLagoas) {
        $this->dbh->exec("SET lc_time_names = 'pt_BR';");

        switch($campo) {
            case 'dia':
                $campoSelect = ' DAY(data)   AS dia ';
                break;
            case 'mes':
                $campoSelect = ' MONTH(data) AS mes,  MONTHNAME(data) nome_mes';
                break;
            case 'ano':
                $campoSelect = ' YEAR(data)  AS ano ';
                break;
            case 'hora':
                $campoSelect = ' HOUR(data)  AS hora ';
                break;
        }

        $sth = $this->dbh->prepare("
            SELECT DISTINCT
                $campoSelect
            FROM
                lagoa l 
                    JOIN coleta c 
                    ON c.id_lagoa = l.id_lagoa 
            WHERE
                l.id_lagoa IN ($idLagoas) AND c.tipo_periodo = :tipoPeriodo
            ORDER BY $campo ASC
        ");

        $idLagoa = $this->getId();
        $sth->execute(array(
            ':tipoPeriodo' => $tipoPeriodo
        ));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $this->assocArray($sth->fetchAll(), $campo, (($campo == 'mes') ? 'nome_mes' : $campo));
    }
}

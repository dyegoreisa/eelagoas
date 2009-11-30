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

    public function listarSelectAssoc( $idProjeto ) {
        
        $sth = $this->dbh->prepare("
            SELECT 
                id_lagoa 
                , nome 
            FROM 
                lagoa 
            WHERE
                id_projeto = $idProjeto
            ORDER BY nome
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $lista = $sth->fetchAll();

        $lista2 = array();
        foreach( $lista as $item ) {
            $lista2[$item['id_lagoa']] = $item['nome'];
        }

        return $lista2;
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
}

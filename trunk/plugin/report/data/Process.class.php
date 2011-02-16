<?php
class Process
{
    private $filters;
    private $dbh;
    private $order;

    public function __construct(array $filters)
    {
        $this->data = array();
        $this->filters = $filters;
        $this->connect();
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }
    
    private function connect()
    {
        try {
            $this->dbh = new PDO(DATA_DRIVER . ':host=' . DATA_HOST . ';dbname=' . DATA_NAME, 
                                 DATA_USER, 
                                 DATA_PASSWD,
                                 array(PDO::ATTR_PERSISTENT => true));
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    private function generateClausula()
    {
        $where = array();

        foreach ($this->filters as $key => $val) {
            switch ($key) {
                case 'projeto':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'pr.id_projeto IN (' . implode(',', $val) . ')';
                    }
                    break;
                case 'lagoa':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'l.id_lagoa IN (' . implode(',', $val) . ')';
                    }
                    break;

                case 'ponto_amostral':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'pa.id_ponto_amostral IN (' . implode(',', $val) . ')';
                    }
                    break;

                case 'categoria':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'ca.id_categoria IN (' . implode(',', $val) . ')';
                    }
                    break;

                case 'parametro':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'c.id_coleta IN (
                                        SELECT DISTINCT 
                                            cp.id_coleta 
                                        FROM parametro p 
                                            JOIN coleta_parametro cp ON cp.id_parametro = p.id_parametro 
                                        WHERE p.id_parametro IN (' . implode(',', $val) . ') 
                                    )';
                    }
                    break;

                case 'especie':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'c.id_coleta IN (
                                        SELECT DISTINCT 
                                            cp.id_coleta 
                                        FROM especie e 
                                            JOIN coleta_parametro_especie cpe ON cpe.id_especie = e.id_especie 
                                            JOIN coleta_parametro cp ON cp.id_coleta_parametro = cpe.id_coleta_parametro 
                                        WHERE e.id_especie IN (' . implode(',', $val) . ')
                                    )';
                    }
                    break;

                case 'tipo_periodo':
                    $where[] = "c.tipo_periodo = '{$val}'";
                    break;

                case 'dia':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'DAY(c.data) IN (' . implode(',', $val) . ')';
                    }
                    break;

                case 'mes':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'MONTH(c.data) IN (' . implode(',', $val) . ')';
                    }
                    break;

                case 'ano':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'YEAR(c.data) IN (' . implode(',', $val) . ')';
                    }
                    break;

                case 'hora':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'HOUR(c.data) IN (' . implode(',', $val) . ')';
                    }
                    break;

                case 'profundidade':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'c.profundidade IN (' . implode(', ', $val) . ')';
                    }
                    break;

            }
        }

        return implode(' AND ', $where);
    }

    public function execute()
    {
        if ($this->filters['tipo_periodo'] == 'mensal') {
            $formatoData = "date_format(c.data, '%m/%Y %H') AS data ";
        } else {
            $formatoData = "date_format(c.data, '%d/%m/%Y %H') AS data ";
        }

        $clausulas = $this->generateClausula();

        $sth = $this->dbh->prepare("
            SELECT /* ************** COLETA ************* */
                c.id_coleta
                , {$formatoData}
                , pr.nome AS nome_projeto
                , l.nome AS nome_lagoa
                , pa.nome AS nome_ponto_amostral
                , ca.nome AS nome_categoria
                , c.profundidade
                , 'parametro'
            FROM projeto pr
                JOIN lagoa l ON l.id_projeto = pr.id_projeto
                JOIN ponto_amostral pa ON pa.id_lagoa = l.id_lagoa
                JOIN coleta c ON c.id_lagoa = l.id_lagoa 
                    AND c.id_ponto_amostral = pa.id_ponto_amostral
                JOIN categoria ca ON ca.id_categoria = c.id_categoria
            WHERE {$clausulas}
        ");

        $sth->execute();

        $sth->setFetchMode(PDO::FETCH_ASSOC);

        $dados = array();
        foreach ($sth->fetchAll() as $val) {
            $val['parametro'] = $this->executeParametro($val['id_coleta']);
            $dados[] = new data_model_coleta($val);
        }
        return $dados;
    }

    private function executeParametro($idColeta)
    {
        $sth = $this->dbh->prepare("
            SELECT
                p.id_parametro
                , p.nome AS parametro
                , cp.valor
                , p.composicao
            FROM coleta c
                JOIN coleta_parametro cp ON cp.id_coleta = c.id_coleta
                JOIN parametro p ON p.id_parametro = cp.id_parametro
            WHERE c.id_coleta = :id_coleta
        ");

        $sth->execute(array(':id_coleta' => $idColeta));

        $sth->setFetchMode(PDO::FETCH_ASSOC);

        $dados = array();
        foreach ($sth->fetchAll() as $val) {
            if ($val['composicao'] == 1) {
                $val['composicao'] = $this->executeEspecie($idColeta, $val['id_parametro']);
            }
            $dados[] = new data_model_parametro($val);
        }
        return $dados;
    }

    private function executeEspecie($idColeta, $idParametro)
    {
        $sth = $this->dbh->prepare("
            SELECT
                e.id_especie
                , cp.id_parametro
                , e.nome AS especie
                , cpe.quantidade
            FROM especie e
                JOIN coleta_parametro_especie cpe ON cpe.id_especie = e.id_especie
                JOIN coleta_parametro cp ON cp.id_coleta_parametro = cpe.id_coleta_parametro
            WHERE cp.id_parametro = :id_parametro
                AND cp.id_coleta = :id_coleta
        ");

        $sth->execute(array(
            ':id_parametro' => $idParametro,
            ':id_coleta'    => $idColeta
        ));

        $sth->setFetchMode(PDO::FETCH_ASSOC);

        $dados = array();
        foreach ($sth->fetchAll() as $val) {
            $dados[] = new data_model_especie($val);
        }
        return $dados;
    }

    public function temComposicao()
    {
        $clausulas = $this->generateClausula();

        $sthComposicao = $this->dbh->prepare("
            SELECT /* *********** TEM COMPOSICAO ************* */
                sum(p.composicao) AS tem_composisao
            FROM coleta c 
                JOIN coleta_parametro cp ON cp.id_coleta = c.id_coleta 
                JOIN parametro p ON p.id_parametro = cp.id_parametro 
                JOIN lagoa l ON l.id_lagoa = c.id_lagoa 
                JOIN projeto pr ON pr.id_projeto = l.id_projeto 
                JOIN categoria ca ON ca.id_categoria = c.id_categoria
                JOIN ponto_amostral pa ON pa.id_ponto_amostral = c.id_ponto_amostral
            WHERE {$clausulas}
        ");
        $sthComposicao->execute();
        $sthComposicao->setFetchMode(PDO::FETCH_ASSOC);

        $dado = $sthComposicao->fetch();
        
        return ((int)$dado['tem_composisao'] > 0) ? true : false;
    }

    public function getTitulosParametro()
    {
        $clausulas = $this->generateClausula();

        $sth = $this->dbh->prepare("
            SELECT DISTINCT /* ***************** PARAMETRO ************** */
                p.id_parametro 
                , p.nome
                , p.composicao 
            FROM coleta c 
                JOIN coleta_parametro cp ON cp.id_coleta = c.id_coleta 
                JOIN parametro p ON p.id_parametro = cp.id_parametro 
                JOIN lagoa l ON l.id_lagoa = c.id_lagoa 
                JOIN projeto pr ON pr.id_projeto = l.id_projeto 
                JOIN categoria ca ON ca.id_categoria = c.id_categoria
                JOIN ponto_amostral pa ON pa.id_ponto_amostral = c.id_ponto_amostral
            WHERE {$clausulas}
            ORDER BY p.composicao, p.nome
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);

        $height = ($this->temComposicao()) ? 2 : 1;

        $dados = array();
        foreach ($sth->fetchAll() as $val) {
            $coluna = new Column;
            $coluna->setText($val['nome']);
            $coluna->setId($val['id_parametro']);
            $coluna->setField('id_parametro');
            $coluna->setHeight($height);
            if ($val['composicao'] == 1) {
                $coluna->setColumns($this->getTitulosEspecie($val['id_parametro']));
            }
            $dados[] = $coluna;
        }
        return $dados;
    }

    private function getTitulosEspecie($idParametro)
    {
        $clausulas = $this->generateClausula();

        $sth = $this->dbh->prepare("
            SELECT DISTINCT /* ********* ESPECIE ************* */
                e.id_especie 
                , e.nome 
            FROM especie e 
                JOIN coleta_parametro_especie cpe ON cpe.id_especie = e.id_especie 
                JOIN coleta_parametro cp ON cp.id_coleta_parametro = cpe.id_coleta_parametro 
                JOIN coleta c ON c.id_coleta = cp.id_coleta 
                JOIN lagoa l ON l.id_lagoa = c.id_lagoa 
                JOIN projeto pr ON pr.id_projeto = l.id_projeto 
                JOIN categoria ca ON ca.id_categoria = c.id_categoria
                JOIN ponto_amostral pa ON pa.id_ponto_amostral = c.id_ponto_amostral
            WHERE {$clausulas} 
                AND cp.id_parametro = :id_parametro
            ORDER BY e.nome
        ");

        $sth->execute(array(':id_parametro' => $idParametro));
        $sth->setFetchMode(PDO::FETCH_ASSOC);

        $dados = array();
        foreach ($sth->fetchAll() as $val) {
            $coluna = new Column;
            $coluna->setText($val['nome']);
            $coluna->setId($val['id_especie']);
            $coluna->setField('id_especie');
            $coluna->setHeight(1);
            $dados[] = $coluna;
        }
        return $dados;
    }
}


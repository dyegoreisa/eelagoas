<?php
class Process
{
    private $filters;
    private $dbh;
    private $order;

    public function __construct($filters = array())
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

    private function generateWhere()
    {
        Mensagem::begin();
        Mensagem::setSeparador('<br>');

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

                case 'categorias':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'ca.id_categoria IN (' . implode(',', $val) . ')';
                    }
                    break;

                case 'parametro':
                    if (is_array($val) && count($val) > 0) {
                        $where[] = 'p.id_parametro IN (' . implode(',', $val) . ')';
                    }
                    break;

                case 'tipo_periodo':
                    $where[] = "c.tipo_periodo = '{$val}'";
                    break;

                case 'dia':
                    $where[] = 'DAY(c.data) IN (' . implode(',', $val) . ')';
                    break;

                case 'mes':
                    $where[] = 'MONTH(c.data) IN (' . implode(',', $val) . ')';
                    break;

                case 'ano':
                    $where[] = 'YEAR(c.data) IN (' . implode(',', $val) . ')';
                    break;

                case 'hora':
                    $where[] = 'HOUR(c.data) IN (' . implode(',', $val) . ')';
                    break;

            }
        }

        return implode(' AND ', $where);
    }

    public function execute()
    {
        if ($this->filters['tipo_periodo'] == 'mensal') {
            $formatoData = "date_format(c.data, '%m/%Y %H') AS data";
        } else {
            $formatoData = "date_format(c.data, '%d/%m/%Y %H') AS data";
        }

        $clausulaWhere = $this->generateWhere();
        $order         = $this->getOrder();

        $sql = "
            SELECT
                $formatoData
                , pr.nome                  AS nome_projeto
                , l.nome                   AS nome_lagoa 
                , pa.nome                  AS nome_ponto_amostral 
                , ca.nome                  AS nome_categoria 
                , p.nome                   AS nome_parametro 
                , p.id_parametro
                , c.id_coleta
                , pe.tabela
                , ce.descricao             AS nome_categoria_extra
                , cp.valor 
                , cp.valor_categoria_extra
            FROM
                coleta c 
                    JOIN lagoa l 		     ON c.id_lagoa = l.id_lagoa 
                    JOIN projeto pr          ON pr.id_projeto = l.id_projeto
                    JOIN ponto_amostral pa 	 ON c.id_ponto_amostral = pa.id_ponto_amostral 
                    JOIN categoria ca 		 ON c.id_categoria = ca.id_categoria 
                    JOIN categoria_extra ce  ON ce.id_categoria_extra = ca.id_categoria_extra
                    JOIN coleta_parametro cp ON c.id_coleta = cp.id_coleta 
                    JOIN parametro p 		 ON cp.id_parametro = p.id_parametro 
                    JOIN parametro_extra pe  ON pe.id_parametro_extra = p.id_parametro_extra
            WHERE 
                $clausulaWhere
            ORDER BY $order
        ";

        $sth = $this->dbh->prepare($sql);

        $sth->execute();

        $sth->setFetchMode(PDO::FETCH_ASSOC);

        $dados = array();
        foreach ($sth->fetchAll() as $val) {
            $dados[] = new Result($val);
        }

        return $dados;
    }

    public function getExtrasByParametro($idParametro, $idColeta, $tabela)
    {
        $sth = $this->dbh->prepare("
            SELECT DISTINCT
                e.id_{$tabela}
                , e.nome
                , pe.descricao
            FROM
                {$tabela} e
                JOIN coleta_parametro_{$tabela} cpe ON e.id_{$tabela} = cpe.id_{$tabela}
                JOIN parametro p ON p.id_parametro = e.id_parametro
                JOIN coleta_parametro cp ON cp.id_parametro = p.id_parametro
                JOIN parametro_extra pe ON pe.id_parametro_extra = p.id_parametro_extra
            WHERE
                p.id_parametro = ? AND cp.id_coleta = ?
            ORDER BY e.nome
        ");

        $sth->execute(array($idParametro, $idColeta));

        $sth->setFetchMode(PDO::FETCH_ASSOC);

        $dados = array();
        foreach ($sth->fetchAll() as $val) {
            $dados[] = new Result($val);
        }

        return $dados;
    }
}


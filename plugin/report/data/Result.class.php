<?php
class Result
{
    private $filters;
    private $dbh;
    private $order;

    public function __construct()
    {
        $this->data = array();
    }

    public function setFilters(array $filters)
    {
        $this->filters = $filters;
    }

    public function setDBH($dbh)
    {
        $this->dbh = $dbh;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }
    
    public function generateWhere()
    {
        Mensagem::begin();
        Mensagem::setSeparador('<br>');

        $where = array();

        foreach ($this->filters as $key => $val) {
            switch ($key) {
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

                case 'id_categoria':
                    if ($val != '') {
                        $where[] = "ca.id_categoria = {$val}";
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

                case 'data_inicio':
                    if (preg_match('/\d{2}\/\d{4}/', $val) === false) {
                        Mensagem::addAtencao('A data incial informada est&aacute; no formato incorreto, 
                                              favor informar no formato (mm/aaaa). O campo foi ignorado');
                    } elseif ($val != '') {
                        $where[] = "c.data >= '" . preg_replace('/(\d{2})\/(\d{4})/', '\2-\1', $val) . '-01' . "'";
                    }
                    break;
                case 'data_fim':
                    if (preg_match('/\d{2}\/\d{4}/', $val) === false) {
                        Mensagem::addAtencao('A data final informada est&aacute; no formato incorreto, 
                                              favor informar no formato (mm/aaaa). O campo foi ignorado');
                    } elseif ($val != '') {
                        $where[] = "c.data <= '" . preg_replace('/(\d{2})\/(\d{4})/', '\2-\1', $val) . '-01' . "'";
                    }
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
                , l.nome   AS nome_lagoa 
                , pa.nome  AS nome_ponto_amostral 
                , ca.nome  AS nome_categoria 
                , p.nome   AS nome_parametro 
                , cp.valor 
            FROM
                coleta c 
                    JOIN lagoa l ON c.id_lagoa = l.id_lagoa 
                    JOIN ponto_amostral pa ON c.id_ponto_amostral = pa.id_ponto_amostral 
                    JOIN categoria ca ON c.id_categoria = ca.id_categoria 
                    JOIN coleta_parametro cp ON c.id_coleta = cp.id_coleta 
                    JOIN parametro p ON cp.id_parametro = p.id_parametro
            WHERE 
                $clausulaWhere
            ORDER BY $order
        ";

        $sth = $this->dbh->prepare($sql);

        $sth->execute();

        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $sth->fetchAll();
    }
}


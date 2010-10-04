<?php

require_once 'Base.class.php';

class import_models_coleta extends import_models_base
{
    private $idColeta;
    private $idLagoa;
    private $nomeLagoa;
    private $idPontoAmostral;
    private $nomePontoAmostral;
    private $idCategoria;
    private $nomeCategoria;
    private $data;
    private $tipoPeriodo;
    private $profundidade;
    private $parametros;

    public function __set($propriedade, $valor)
    {
        if (property_exists($this, $propriedade)) {
            switch ($propriedade)
            {
                case 'profundidade':
                    $this->$propriedade = (empty($valor)) ? 0 : $valor;
                    break;

                case 'tipoPeriodo':
                    $this->$propriedade = ($valor == 1) ? 'mensal' : 'diario';
                    break;

                case 'data':
                    $this->formataData($valor);
                    break;

                default:
                    $this->$propriedade = $valor;
            }
        } else {
            throw new Exception("SET: Propriedade {$propriedade} não existe na classe Coleta.");
        }
    }

    public function __get($propriedade) 
    {
        if (property_exists($this, $propriedade)) {
            return $this->$propriedade;
        } else {
            throw new Exception("GET: Propriedade {$propriedade} não existe na classe Coleta.");
        }
    }

    private function formataData($valor)
    {
        $padraoDMAHM = '/^([1-9]|0[1-9]|[12][0-9]|3[01])\/([1-9]|0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])(:.+)/';
        $padraoDMAH  = '/^([1-9]|0[1-9]|[12][0-9]|3[01])\/([1-9]|0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])$/';
        $padraoMAHM  = '/^([1-9]|0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])(:.+)/';
        $padraoMAH   = '/^([1-9]|0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])$/';
        $padraoMA    = '/^([1-9]|0[1-9]|1[012])\/([12][0-9]{3})$/';

        if (preg_match($padraoDMAHM, $valor)) {
            $this->data = preg_replace($padraoDMAHM, '\3-\2-\1 \4:00:00', $valor);
        } elseif (preg_match($padraoDMAH, $valor)) {
            $this->data = preg_replace($padraoDMAH, '\3-\2-\1 \4:00:00', $valor);
        } elseif (preg_match($padraoMAHM, $valor)) {
            $this->data = preg_replace($padraoMAHM, '\2-\1-01 \3:00:00', $valor);
        } elseif (preg_match($padraoMAH, $valor)) {
            $this->data = preg_replace($padraoMAH, '\2-\1-01 \3:00:00', $valor);
        } elseif (preg_match($padraoMA, $valor)) {
            $this->data = preg_replace($padraoMA, '\2-\1-01 00:00:00', $valor);
        } else {
            $this->data = '';
        }

        $this->corrigeData();

    }

    private function corrigeData()
    {
        list($data, $hora) = explode(' ', $this->data);
        $arrayData = explode('-',$data);
        $arrayHora = explode(':', $hora);

        foreach ($arrayData as &$val) {
            if (strlen($val) == 1) {
                $val = "0{$val}";
            }
        }
        foreach ($arrayHora as &$val) {
            if (strlen($val) == 1) {
                $val = "0{$val}";
            }
        }

        $data = implode('-', $arrayData);
        $hora = implode(':', $arrayHora);
        $this->data = "{$data} {$hora}";
    }

    public function salvar($idProjeto)
    {
        if (
            $this->nomeLagoa         != '' &&
            $this->nomePontoAmostral != '' &&
            $this->nomeCategoria     != '' &&
            $this->data              != '' &&
            $this->tipoPeriodo       != '' 
        ) {
            $this->idLagoa         = $this->inserirLagoa($idProjeto, $this->nomeLagoa);
            $this->idPontoAmostral = $this->inserirPontoAmostral($this->idLagoa, $this->nomePontoAmostral);
            $this->idCategoria     = $this->inserirCategoria($this->nomeCategoria);
            $this->profundidade    = empty($this->profundidade) ? 0 : $this->profundidade;

            $dados = array(
                ':idLagoa'         => $this->idLagoa,
                ':idPontoAmostral' => $this->idPontoAmostral,
                ':idCategoria'     => $this->idCategoria,
                ':data'            => $this->data,
                ':tipoPeriodo'     => $this->tipoPeriodo,
                ':profundidade'    => $this->profundidade
            );

            $sthSelect = $this->dbh->prepare("
                SELECT id_coleta
                FROM coleta
                WHERE id_lagoa = :idLagoa
                    AND id_ponto_amostral = :idPontoAmostral
                    AND id_categoria = :idCategoria
                    AND data = :data
                    AND tipo_periodo = :tipoPeriodo
                    AND profundidade = :profundidade
            ");
            $sthSelect->execute($dados);
            $sthSelect->setFetchMode(PDO::FETCH_ASSOC);
            $coleta = $sthSelect->fetch();
            $this->idColeta = $coleta['id_coleta'];

            if (empty($this->idColeta)) {
                $sthInsert = $this->dbh->prepare("
                    INSERT INTO coleta (
                        id_lagoa
                        , id_ponto_amostral
                        , id_categoria
                        , data
                        , tipo_periodo
                        , profundidade
                    ) 
                    VALUES (
                        :idLagoa
                        , :idPontoAmostral
                        , :idCategoria
                        , :data
                        , :tipoPeriodo
                        , :profundidade
                    )
                ");
                $ok = $sthInsert->execute($dados);
                if ($ok) {
                    $this->idColeta = $this->dbh->lastInsertId();
                } else {
                    return false;
                }
            } else {
                $this->idColeta = $coleta['id_coleta'];
            }

            return $this->idColeta;
        } else {
            return false;
        }
    }

}

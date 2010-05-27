<?php

require_once SPREADSHEET . 'Reader.php';
require_once 'models/Coleta.class.php'; 
require_once 'models/ColetaParametro.class.php'; 
require_once 'models/ColetaParametroEspecie.class.php'; 
require_once 'models/Conexao.class.php';

class ImportadorExcel 
{
    private $file;
    private $data;
    private $listaParametros;
    private $listaEspecies;
    private $especiesPorComposicao;
    private $dbh;

    public function __construct()
    {
        $this->data = new Spreadsheet_Excel_Reader();
        $this->data->setOutputEncoding('CP1251');

        $conexao   = new import_models_conexao();
        $this->dbh = $conexao->getDbh();
    }

    public function setFile($file)
    {
        $this->file = $file;
        //$this->data->setOutputEncoding('CPa25a');
        //$this->data->setDefaultFormat('%.2f');
        $this->data->setDefaultFormat(0);
        $this->data->setColumnFormat(1, 'd/m/Y H');
        $this->data->setColumnFormat(2, '@');
        $this->data->setColumnFormat(3, '@');
        $this->data->setColumnFormat(4, '0');
        $this->data->setColumnFormat(5, '@');
        $this->data->setColumnFormat(6, '0.0');

        $this->data->read($this->file);
    }

    public function getArrayData()
    {
        $dados = array();
        for ($i = 1; $i <= $this->data->sheets[0]['numRows']; $i++) {
            for ($j = 1; $j <= $this->data->sheets[0]['numCols']; $j++) {
                if ($this->data->sheets[0]['cells'][$i][$j] !== '') {
                    $dados[$i][$j] = array(
                        'data' => $this->data->sheets[0]['cells'][$i][$j],
                        'cellInfo' => $this->data->sheets[0]['cellsInfo'][$i][$j]
                    );
                } else {
                    $dados[$i][$j] = '';
                }
            }
        }

        $cabecalho[] = array_shift($dados);
        $cabecalho[] = array_shift($dados);
        $cabecalho[] = array_shift($dados);

        return array(
            'cabecalho' => $cabecalho,
            'dados'     => $dados,
            'excel'     => $this->data
        );
    }

    /**
     * Insere dados no banco de uma planilha excel
     * 
     * @param array $dados 
     * @access public
     * @return int - 0 para erro, 1 para Ok e 2 para alguns erros
     */
    public function insertData(array $dados) 
    {
        $this->data            = $dados['excel'];
        $idProjeto             = $dados['id_projeto'];
        $this->listaParametros = $this->data->sheets[0]['cells'][2];
        $this->listaEspecies   = $this->data->sheets[0]['cells'][3];

        $this->setEspeciesPorComposicao();

        return $this->montaArrayColeta($idProjeto);
    }

    private function setEspeciesPorComposicao()
    {
        $this->especiesPorComposicao = array();
        foreach ($this->listaParametros as $key => $parametro) {
            if (!empty($parametro) && !empty($this->listaEspecies[$key])) {
                for ($i = $key; $i <= count($this->listaEspecies); $i++) {
                    $this->especiesPorComposicao[$i] = array(
                        'nomeComposicao' => $parametro,
                        'nomeEspecie'    => $this->listaEspecies[$i],
                    );
                }
            }
        }
    }

    private function montaArrayColeta($idProjeto)
    {
        // Para inserir dados da coleta
        $arrayColetas = array();
        $indicesColeta = array(
            1 => 'data', 
            2 => 'nomeLagoa', 
            3 => 'nomePontoAmostral', 
            4 => 'tipoPeriodo', 
            5 => 'nomeCategoria', 
            6 => 'profundidade', 
            7 => 'parametros'
        );

        $this->dbh->beginTransaction();
        $ok = 0;
        $er = 0;
        // Pega os dados diretamente relacionados a coleta. Pulando o cabeçalho
        for ($x = 4; $x < $this->data->sheets[0]['numRows']; $x++) {
            $tpl = array();
            $tmpColeta = new import_models_coleta($this->dbh);

            // Pega os campos da coleta
            foreach ($indicesColeta as $key => $item) {
                if ($item !== 'parametros') {
                    $tmpColeta->$item = $this->data->sheets[0]['cells'][$x][$key];
                }
            }
            if ($tmpColeta->salvar($idProjeto) !== false) {
                $erro      = $this->montaArrayParametro($tmpColeta, $x, count($indicesColeta));
                $erroMaior = ($erro > $erroMaior) ? $erro : $erroMaior;
                $ok++;
            } else {
                $er++;
            }

            $arrayColetas[] = $tmpColeta;
        }

        if ($ok == 0) {
            $this->dbh->rollBack();
            Mensagem::addAtencao(latinToUTF("Não foi possível inserir as coletas."));
            $saida = 0;
        } else {
            $this->dbh->commit();
            $saida = ($er == 0 && $erroMaior == 1) ? 1 : 2;
        }

        return $saida;
    }

    private function montaArrayParametro(import_models_coleta $coleta, $x, $y)
    {
        $ok = 0;
        $er = 0;
        $arrayParametros = array();
        for (; $y <= $this->data->sheets[0]['numRows']; $y++) {
            
            // Verifica se tem valor na celula para pegar o parametro
            if (!empty($this->data->sheets[0]['cells'][$x][$y])) {
                $tmpColetaParametro = new import_models_coletaParametro($this->dbh);

                // Verifica se é composição
                if (isset($this->especiesPorComposicao[$y])) {
                    if ($nomeComposicao != $this->especiesPorComposicao[$y]['nomeComposicao']) {
                        $nomeComposicao                    = $this->especiesPorComposicao[$y]['nomeComposicao'];
                        $tmpColetaParametro->nomeParametro = $this->especiesPorComposicao[$y]['nomeComposicao'];
                        $tmpColetaParametro->composicao    = true;

                        if ($tmpColetaParametro->salvar($coleta->idColeta) !== false ) {
                            $erro      = $this->montaArrayEspecie($tmpColetaParametro, $nomeComposicao, $x, $y);
                            $erroMaior = ($erro > $erroMaior) ? $erro : $erroMaior;
                            $ok++;
                        } else {
                            $er++;
                        }

                        $arrayParametros[] = $tmpColetaParametro;
                    }
                } elseif (!empty($this->data->sheets[0]['cells'][2][$y])) { // Parametros que não composição
                    $tmpColetaParametro->nomeParametro = $this->data->sheets[0]['cells'][2][$y];
                    $tmpColetaParametro->valor         = $this->data->sheets[0]['cells'][$x][$y];

                    if ($tmpColetaParametro->salvar($coleta->idColeta) !== false ) {
                        $ok++;
                    } else {
                        $er++;
                    }
                    $erroMaior = 1;

                    $arrayParametros[] = $tmpColetaParametro;
                }
            }
        }

        if ($ok == 0) {
            $this->dbh->rollBack();
            $this->dbh->beginTransaction();
            switch($coleta->tipoPeriodo)
            {
                case 'diario':
                    $data = date("d/m/Y h", strtotime($coleta->data));
                    break;

                case 'mensal':
                    $data = date("m/Y h", strtotime($coleta->data));
                    break;
            }
            Mensagem::addAtencao(latinToUTF("
                Não foi possível inserir alguns parametros da coleta {$data}
                - {$coleta->nomeLagoa}
                - {$coleta->nomePontoAmostral}
                - {$coleta->nomeCategoria}.
            "));
            $saida = 0;
        } else {
            $saida = ($er == 0 && $erroMaior == 1) ? 1 : 2;
        }

        $coleta->parametros = $arrayParametros;

        return $saida;
    }

    private function montaArrayEspecie(import_models_coletaParametro $coletaParametro, $nomeComposicao, $x, $y)
    {
        $ok = 0;
        $er = 0;
        $arrayEspecies = array();
        foreach ($this->especiesPorComposicao as $key => $item) {
            if ($nomeComposicao == $item['nomeComposicao']) {
                if (!empty($this->data->sheets[0]['cells'][$x][$key])) {
                    $tmpColetaParametroEspecie = new import_models_coletaParametroEspecie($this->dbh);
                    $tmpColetaParametroEspecie->nomeEspecie = $item['nomeEspecie'];
                    $tmpColetaParametroEspecie->quantidade  = $this->data->sheets[0]['cells'][$x][$key];

                    if ($tmpColetaParametroEspecie->salvar($coletaParametro->idColetaParametro, $coletaParametro->idParametro) !== false) {
                        $ok++;
                    } else {
                        $er++;
                    }

                    $arrayEspecies[] = $tmpColetaParametroEspecie;
                }
            }
        }

        if ($ok == 0) {
            $this->dbh->rollBack();
            $this->dbh->beginTransaction();
            Mensagem::addAtencao(latinToUTF("Não foi possível inserir algumas especies do parametro {$coletaParametro->nomeParametro}."));
            $saida = 0;
        } else {
            $saida = ($er == 0) ? 1 : 2;
        }

        $coletaParametro->especies = $arrayEspecies;

        return $saida;
    }
}
?>

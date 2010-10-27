<?php
require_once 'plugin/import/model/Lagoa.class.php';
require_once 'plugin/import/model/PontoAmostral.class.php';
require_once 'plugin/import/model/Categoria.class.php';
require_once 'plugin/import/model/Parametro.class.php';
require_once 'plugin/import/model/Especie.class.php';
require_once 'plugin/import/model/ColetaParametro.class.php';
require_once 'plugin/import/model/ColetaParametroEspecie.class.php';

class dao_excel
{
    private $idProjeto;
    private $excel;
    private $coletas;

    public function __construct($idProjeto, Spreadsheet_Excel_Reader $excel)
    {
        $this->idProjeto = $idProjeto;
        $this->excel     = $excel;
        $this->process();
    }

    public function getColetas()
    {
        return $this->coletas;
    }

    private function process()
    {
        $contaLinhaVazia = 0;
        for ($row = 4; $row < $this->excel->rowcount(); $row++) {
            
            $lagoa = new model_lagoa();
            $lagoa->setNome($this->excel->value($row, 'B'));
            $lagoa->setIdProjeto($this->idProjeto);

            $pontoAmostral = new model_pontoAmostral();
            $pontoAmostral->setNome($this->excel->value($row, 'C'));
            $pontoAmostral->setLagoa($lagoa);

            $categoria = new model_categoria();
            $categoria->setNome($this->excel->value($row, 'E'));
            $categoria->setEPerfil(($this->excel->value($row, 'F') === '') ? 0 : 1);

            $coleta = new model_coleta();
            $coleta->setData($this->excel->value($row, 'A'));
            $coleta->setTipoPeriodo(($this->excel->value($row, 'D') == 0) ? 'diario' : 'mensal');
            $coleta->setProfundidade($this->excel->value($row, 'F'));

            $coleta->setLagoa($lagoa);
            $coleta->setPontoAmostral($pontoAmostral);
            $coleta->setCategoria($categoria);

            if ($coleta->getData()        != '' &&
                $lagoa->getNome()         != '' &&
                $pontoAmostral->getNome() != '' &&
                $categoria->getNome()     != '' &&
                $coleta->getTipoPeriodo() != '') 
            {
                $this->buscaParametrosDaColeta($coleta, $row);
                $this->coletas[] = $coleta;
            } else {
                if ($coleta->getData()        == '' &&
                    $lagoa->getNome()         == '' &&
                    $pontoAmostral->getNome() == '' &&
                    $categoria->getNome()     == '')
                {
                    $contaLinhaVazia++;
                } else {
                    Mensagem::addErro(latinToUTF('Não foi possível salvar a coleta ' . $coleta->getMensagem()));
                }

            }
        }
        if ($contaLinhaVazia) {
            Mensagem::addErro(latinToUTF('Não foram importadas as ' . $contaLinhaVazia . ' linhas vazias'));
        }
    }

    private function buscaParametrosDaColeta(model_coleta $coleta, $row)
    {
        for ($col = 7; $col <= $this->excel->colcount(); $col++) {
            $valor = $this->excel->value($row, $col);

            if (!empty($valor)) {
                $parametro = new model_parametro();

                $coletaParametro = new model_coletaParametro();
                $coletaParametro->setColeta($coleta);

                if ($this->parametroEComposicao($col)) {
                    $parametro->setNome($this->nomeDaComposicao($col));
                    $parametro->setComposicao(TRUE);

                    $coletaParametro->setParametro($parametro);
                    $coletaParametro->setValor($this->quantidadeDeEspeciesNaComposicaoReal($row, $col));

                    $quantidadeDeEspecies = $this->quantidadeDeEspeciesNaComposicao($col);
                    $coletaParametroEspecies = array();
                    for ($i = 0; $i < $quantidadeDeEspecies; $i++, $col++) {
                        $quantidade = $this->excel->value($row, $col);

                        if (!empty($quantidade)) {
                            $especie = new model_especie();
                            $especie->setNome($this->excel->value(3, $col));
                            $especie->setParametro($parametro);

                            $coletaParametroEspecie = new model_coletaParametroEspecie();
                            $coletaParametroEspecie->setColetaParametro($coletaParametro);
                            $coletaParametroEspecie->setEspecie($especie);
                            $coletaParametroEspecie->setQuantidade($quantidade);

                            $parametro->addEspecie($coletaParametroEspecie);
                        }
                    }

                    $col--; // Volta 1 para continuar o incremento no for mais externo

                } else {
                    $parametro->setNome($this->excel->value(2, $col));
                    $coletaParametro->setValor($this->excel->value($row, $col));
                    $coletaParametro->setParametro($parametro);
                }

                $coleta->addParametro($coletaParametro);
            }
        }
        return $coletaParametros;
    }

    private function quantidadeDeEspeciesNaComposicao($col)
    {
        $count = 0;
        while ($this->excel->value(2, $col) == '') {
            $col--;
            $count++;
        }
        return $this->excel->colspan(2, $col) - $count;
    }

    private function quantidadeDeEspeciesNaComposicaoReal($row, $col) 
    {
        $quantidadeDeEspecies = $this->quantidadeDeEspeciesNaComposicao($col);

        $count = 0;
        for ($i = 0; $i < $quantidadeDeEspecies; $i++, $col++) {
            $quantidade = $this->excel->value($row, $col);
            if (!empty($quantidade)) {
                $count++;
            }
        }

        return $count;
    }

    private function parametroEComposicao($col)
    {
        return ($this->excel->value(3, $col) != '') ? TRUE : FALSE;
    }

    private function nomeDaComposicao($col)
    {
        while ($this->excel->value(2, $col) == '') {
            $col--;
        }
        return $this->excel->value(2, $col);
    }
}

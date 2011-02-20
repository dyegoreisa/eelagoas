<?php
require_once 'Base.class.php';
require_once PROC . 'plugin/import/dao/Coleta.class.php';
require_once PROC . 'plugin/import/dao/ColetaParametro.class.php';

class model_coleta extends model_base
{
    /**
     * Objeto lagoa
     * 
     * @var model_lagoa
     * @access private
     */
    private $lagoa;

    /**
     * Objeto pontoAmostral 
     * 
     * @var model_pontoAmostral
     * @access private
     */
    private $pontoAmostral;

    /**
     * categoria 
     * 
     * @var model_categoria
     * @access private
     */
    private $categoria;

    /**
     * Data em formato ISO
     * 
     * @var string
     * @access private
     */
    private $data;

    /**
     * Data em formato variável
     * 
     * @var string
     * @access private
     */
    private $dataOriginal;

    /**
     * Tipo de periodo 
     * 
     * @var boolean
     * @access private
     */
    private $tipoPeriodo;

    /**
     * Profundidade 
     * 
     * @var float
     * @access private
     */
    private $profundidade;

    /**
     * Parametros 
     * 
     * @var array
     * @access private
     */
    private $parametros;

    public function getLagoa() 
    {
        return $this->lagoa;
    }

    public function setLagoa(model_lagoa $lagoa) 
    {
        $this->lagoa = $lagoa;
        $this->addDadoParaBusca(0, $lagoa->getId());
    }

    public function getPontoAmostral() 
    {
        return $this->pontoAmostral;
    }

    public function setPontoAmostral(model_pontoAmostral $pontoAmostral)
    {
        $this->pontoAmostral = $pontoAmostral;
        $this->addDadoParaBusca(1, $pontoAmostral->getId());
    }

    public function getCategoria()
    {
        return $this->categoria;
    }

    public function setCategoria(model_categoria $categoria)
    {
        $this->categoria = $categoria;
        $this->addDadoParaBusca(2, $categoria->getId());
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        $this->dataOriginal = $data;
        $this->formataData();
        $this->addDadoParaBusca(3, $this->data);
    }

    public function getDataOriginal()
    {
        return $this->dataOriginal;
    }

    public function getTipoPeriodo()
    {
        return $this->tipoPeriodo;
    }

    public function setTipoPeriodo($tipoPeriodo)
    {
        $this->tipoPeriodo = $tipoPeriodo;
        $this->addDadoParaBusca(4, $this->tipoPeriodo);
    }

    public function getProfundidade()
    {
        return $this->profundidade;
    }

    public function setProfundidade($profundidade)
    {
        $this->profundidade = $profundidade;
        $this->formataProfundidade();
        $this->addDadoParaBusca(5, $this->profundidade);
    }

    public function addParametro(model_coletaParametro $coletaParametro)
    {
        $this->parametros[] = $coletaParametro;
    }
    
    public function getPropriedades()
    {
        return get_object_vars($this);
    }

    private function formataData()
    {
        $padraoDMAHM = '/^([1-9]|0[1-9]|[12][0-9]|3[01])\/([1-9]|0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])(:.+)/';
        $padraoDMAH  = '/^([1-9]|0[1-9]|[12][0-9]|3[01])\/([1-9]|0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])$/';
        $padraoMAHM  = '/^([1-9]|0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])(:.+)/';
        $padraoMAH   = '/^([1-9]|0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])$/';
        $padraoMA    = '/^([1-9]|0[1-9]|1[012])\/([12][0-9]{3})$/';

        if (preg_match($padraoDMAHM, $this->data)) {
            $this->data = preg_replace($padraoDMAHM, '\3-\2-\1 \4:00:00', $this->data);
        } elseif (preg_match($padraoDMAH, $this->data)) {
            $this->data = preg_replace($padraoDMAH, '\3-\2-\1 \4:00:00', $this->data);
        } elseif (preg_match($padraoMAHM, $this->data)) {
            $this->data = preg_replace($padraoMAHM, '\2-\1-01 \3:00:00', $this->data);
        } elseif (preg_match($padraoMAH, $this->data)) {
            $this->data = preg_replace($padraoMAH, '\2-\1-01 \3:00:00', $this->data);
        } elseif (preg_match($padraoMA, $this->data)) {
            $this->data = preg_replace($padraoMA, '\2-\1-01 00:00:00', $this->data);
        } else {
            $this->data = '';
        }

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
        $dataISO = "{$data} {$hora}";
        if ($dataISO != ' ') {
            $this->data = $dataISO;
        } else {
            $this->data = '';
        }
    }

    private function formataProfundidade()
    {
        $this->profundidade = ($this->profundidade == '') ? 0 : $this->profundidade;
    }

    public function salvarParametros()
    {
        if (is_array($this->parametros)) {
            foreach ($this->parametros as $coletaParametro) {
                if ($coletaParametro->getId()) {
                    $coletaParametro->getParametro()->salvarEspecies();
                } else {
                    Mensagem::addErro(latinToUTF('Não salvou o parametro ' . $coletaParametro->getParametro()->getNome()));
                }
            }
        } else {
            Mensagem::addErro(latinToUTF('Lista de parametros da coleta ' . $this->getMensagem() . ' está vazia'));
        }
    }

    public function getMensagem()
    {
        return 'Data = [' . $this->getDataOriginal() . '] - '
             . 'Lagoa = [' . $this->lagoa->getNome() . '] - '
             . 'Ponto amostral = [' . $this->pontoAmostral->getNome() . '] - '
             . 'Categoria = [' . $this->categoria->getNome() . '] - '
             . 'Período = [' . $this->getTipoPeriodo() . ']';
    }
}

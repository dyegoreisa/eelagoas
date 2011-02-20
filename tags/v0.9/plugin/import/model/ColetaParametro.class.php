<?php
require_once 'Base.class.php';

class model_coletaParametro extends model_base
{
    /**
     * Objeto coleta 
     * 
     * @var model_coleta
     * @access private
     */
    private $coleta;

    /**
     * Objeto parametro 
     * 
     * @var model_parametro
     * @access private
     */
    private $parametro;

    /**
     * Valor para este parametro da coleta 
     * 
     * @var float
     * @access private
     */
    private $valor;

    public function getColeta()
    {
        return $this->coleta;
    }

    public function setColeta(model_coleta $coleta)
    {
        $this->coleta = $coleta;
        $this->addDadoParaBusca(0, $this->coleta->getId());
    }

    public function getParametro()
    {
        return $this->parametro;
    }
    
    public function setParametro(model_parametro $parametro)
    {
        $this->parametro = $parametro;
        $this->addDadoParaBusca(1, $this->parametro->getId());
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getPropriedades()
    {
        return get_object_vars($this);
    }
}

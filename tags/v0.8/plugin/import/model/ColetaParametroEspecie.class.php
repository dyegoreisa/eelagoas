<?php
require_once 'Base.class.php';

class model_coletaParametroEspecie extends model_base
{
    /**
     * Objeto coletaParametro 
     * 
     * @var model_coletaParametro
     * @access private
     */
    private $coletaParametro;

    /**
     * Objeto especie 
     * 
     * @var model_especie
     * @access private
     */
    private $especie;

    /**
     * Quantidade de especie na coleta parametro especie
     * 
     * @var int
     * @access private
     */
    private $quantidade;

    public function getColetaParametro()
    {
        return $this->coletaParametro;
    }

    public function setColetaParametro(model_coletaParametro $coletaParametro)
    {
        $this->coletaParametro = $coletaParametro;
        $this->addDadoParaBusca(0, $this->getColetaParametro()->getId());
    }

    public function getEspecie()
    {
        return $this->especie;
    }

    public function setEspecie(model_especie $especie)
    {
        $this->especie = $especie;
        $this->addDadoParaBusca(1, $this->especie->getId());
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function getPropriedades()
    {
        return get_object_vars($this);
    }
}

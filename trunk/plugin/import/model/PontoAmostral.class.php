<?php
require_once 'Base.class.php';
require_once PROC . 'plugin/import/dao/PontoAmostral.class.php';

class model_pontoAmostral extends model_base
{
    /**
     * Objeto lagoa 
     * 
     * @var model_lagoa
     * @access private
     */
    private $lagoa;

    /**
     * Nome do ponto amostral 
     * 
     * @var string
     * @access private
     */
    private $nome;

    public function getLagoa()
    {
        return $this->lagoa;
    }

    public function setLagoa(model_lagoa $lagoa)
    {
        $this->lagoa = $lagoa;
        $this->addDadoParaBusca(0, $this->lagoa->getId());
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        $this->addDadoParaBusca(1, $this->nome);
    }

    public function getPropriedades()
    {
        return get_object_vars($this);
    }
}

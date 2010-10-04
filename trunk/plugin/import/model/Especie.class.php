<?php
require_once 'Base.class.php';
require_once 'plugin/import/dao/Especie.class.php';

class model_especie extends model_base
{
    /**
     * Objeto parametro 
     * 
     * @var mixed
     * @access private
     */
    private $parametro;

    /**
     * Nome da especie
     * 
     * @var string
     * @access private
     */
    private $nome;

    public function getParametro()
    {
        return $this->parametro;
    }

    public function setParametro($parametro)
    {
        $this->parametro = $parametro;
        $this->addDadoParaBusca(0, $this->parametro->getId());
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        $this->addDadoParaBusca(1, $nome);
    }

    public function getPropriedades()
    {
        return get_object_vars($this);
    }
}

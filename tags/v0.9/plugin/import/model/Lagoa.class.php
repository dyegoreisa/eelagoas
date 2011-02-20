<?php
require_once 'Base.class.php';
require_once PROC . 'plugin/import/dao/Lagoa.class.php';

class model_lagoa extends model_base
{
    /**
     * ID do projeto, neste caso não tem objeto para projeto
     * 
     * @var int
     * @access private
     */
    private $idProjeto;

    /**
     * Nome da lagoa 
     * 
     * @var mixed
     * @access private
     */
    private $nome;

    public function getIdProjeto()
    {
        return $this->idProjeto;
    }

    public function setIdProjeto($idProjeto)
    {
        $this->idProjeto = $idProjeto;
        $this->addDadoParaBusca(0, $this->idProjeto);
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

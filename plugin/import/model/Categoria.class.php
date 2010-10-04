<?php
require_once 'Base.class.php';
require_once 'plugin/import/dao/Categoria.class.php';

class model_categoria extends model_base
{
    /**
     * Nome da categoria
     * 
     * @var string
     * @access private
     */
    private $nome;

    /**
     * Informa de é do tipo que precisa informar a profundidade
     * 
     * @var boolean
     * @access private
     */
    private $ePerfil;

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        $this->addDadoParaBusca(0, $nome);
    }

    public function getEPerfil()
    {
        return $this->ePerfil;
    }

    public function setEPerfil($ePerfil)
    {
        $this->ePerfil = $ePerfil;
    }

    public function getPropriedades()
    {
        return get_object_vars($this);
    }
}

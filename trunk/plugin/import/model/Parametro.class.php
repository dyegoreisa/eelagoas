<?php
require_once 'Base.class.php';
require_once PROC . 'plugin/import/dao/Parametro.class.php';
require_once PROC . 'plugin/import/dao/ColetaParametroEspecie.class.php';

class model_parametro extends model_base
{
    /**
     * Nome do parametro 
     * 
     * @var string
     * @access private
     */
    private $nome;

    /**
     * Informa se é composicao 
     * 
     * @var mixed
     * @access private
     */
    private $composicao;

    /**
     * Quando é uma composição contém um array de espécies
     * 
     * @var array
     * @access private
     */
    private $especies;

    public function __construct()
    {
        $this->composicao = FALSE;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        $this->addDadoParaBusca(0, $nome);
    }

    public function getComposicao()
    {
        return $this->composicao;
    }

    public function setComposicao($composicao)
    {
        $this->composicao = $composicao;
    }

    public function addEspecie(model_coletaParametroEspecie $coletaParametroEspecies)
    {
        $this->especies[] = $coletaParametroEspecies;
        $this->composicao = TRUE;
    }

    public function getPropriedades()
    {
        return get_object_vars($this);
    }

    public function salvarEspecies()
    {
        if ($this->composicao) {
            foreach ($this->especies as $coletaParametroEspecies) {
                if (!$coletaParametroEspecies->getId()) {
                    Mensagem::addErro(latinToUTF('Não salvou a especie ' . $coletaParametroEspecies->getEspecie->getNome()));
                }
            }
        }
    }
}

<?php
abstract class model_base
{
    private $id;
    private $dadosParaBusca;

    abstract public function getPropriedades();

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        if (empty($this->id) && !$this->buscar($this->dadosParaBusca)) {
            $this->salvar();
        }
        return $this->id;   
    }

    public function addDadoParaBusca($indice, $dado)
    {
        $this->dadosParaBusca[$indice] = $dado;
    }

    private function pegaNomeDao()
    {
        return str_replace('model', 'dao', get_class($this));
    }

    private function salvar()
    {
        $nomeClasseDao = $this->pegaNomeDao();
        $dao = new $nomeClasseDao;
        $dao->setModel($this);
        return $dao->salvar();
    }

    private function buscar(array $parametros)
    {
        $nomeClasseDao = $this->pegaNomeDao();
        $dao = new $nomeClasseDao;
        $dao->setModel($this);
        $this->id = $dao->buscarId($parametros);
        return ($this->id) ? TRUE : FALSE;
    }
}

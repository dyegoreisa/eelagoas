<?php
class data_model_parametro
{
    private $id;
    private $nome;
    private $valor;
    private $composicao;
	
    public function __construct(array $colunas)
    {
    	$this->id         = $colunas['id_parametro'];
        $this->nome       = $colunas['parametro'];
        $this->valor      = $colunas['valor'];

        $this->setComposicao($colunas['composicao']);
    }
    
    public function getId()
    {
   		return $this->id;
    }
    
    public function getNome()
    {
    	return $this->nome;
    }
    
    public function getValor()
    {
    	return $this->valor;
    }
    
    public function getComposicao()
    {
    	return $this->composicao;
    }

    public function setComposicao($especies)
    {
        if (is_array($especies)) {
            foreach ($especies as $especie) {
                $this->composicao[$especie->getId()] = $especie;
            }
        } else {
            $this->composicao = $especies;
        }
    }
    
    public function EComposicao()
    {
    	return is_array($this->composicao);
    }
	
    public function getEspecieById($id)
    {
        if (isset($this->composicao[$id])) {
            return $this->composicao[$id];
        }
        return null;
    }
}

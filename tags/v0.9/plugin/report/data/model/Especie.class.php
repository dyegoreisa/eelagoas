<?php
class data_model_especie
{
	private $id;
	private $nome;
	private $quantidade;

	public function __construct(array $colunas)
	{
		$this->id          = $colunas['id_especie'];
        $this->idParametro = $colunas['id_parametro'];
        $this->nome        = $colunas['especie'];
        $this->quantidade  = $colunas['quantidade'];
	}
	
	public function getId()
	{
		return $this->id;
	}

    public function getIdParametro()
    {
        return $this->idParametro;
    }
	
	public function getNome()
	{
		return $this->nome;
	}
	
	public function getQuantidade()
	{
		return $this->quantidade;
	}

    public function getValor()
    {
        return $this->getQuantidade();
    }
}

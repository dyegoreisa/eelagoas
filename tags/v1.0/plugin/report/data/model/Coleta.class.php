<?php
class data_model_coleta
{
    private $id;
    private $data;
    private $nomeProjeto;
    private $nomeLagoa;
    private $nomePontoAmostral;
    private $nomeCategoria;
    private $profundidade;
    private $parametros;
    private $colunas;

    public function __construct(array $colunas)
    {
        $this->id                = $colunas['id_coleta'];
        $this->data              = $colunas['data'];
        $this->nomeProjeto       = $colunas['nome_projeto'];
        $this->nomeLagoa         = $colunas['nome_lagoa'];
        $this->nomePontoAmostral = $colunas['nome_ponto_amostral'];
        $this->nomeCategoria     = $colunas['nome_categoria'];
        $this->profundidade      = $colunas['profundidade'];

        $this->setParametros($colunas['parametro']);

        // Implementado desta forma somente para usar no render PDF
        $this->colunas = $colunas;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getNomeProjeto()
    {
        return $this->nomeProjeto;
    }
    
    public function getNomeLagoa()
    {
        return $this->nomeLagoa;
    }
    
    public function getNomePontoAmostral()
    {
        return $this->nomePontoAmostral;
    }
    
    public function getNomeCategoria()
    {
        return $this->nomeCategoria;
    }
    
    public function getProfundidade()
    {
        return $this->profundidade;
    }
    
    public function getParametros()
    {
        return $this->parametros;
    }

    public function getDataByField($field)
    {
        return $this->colunas[$field];
    }

    public function setParametros(array $parametros)
    {
        foreach ($parametros as $parametro) {
            $this->parametros[$parametro->getId()] = $parametro;
        }
    }

    public function findParametro($id, $field)
    {
        if ($field == 'id_parametro' && isset($this->parametros[$id])) {
            return $this->parametros[$id];
        } elseif ($field == 'id_especie') {
            return $this->findEspecie($id);
        }
        return null;
    }

    private function findEspecie($id)
    {
        foreach ($this->parametros as $parametro) {
            if ($parametro->EComposicao()) {
                $especie = $parametro->getEspecieById($id);
                if (isset($especie)) {
                    return $especie;
                }
            }
        }

        return null;
    }
}

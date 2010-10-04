<?php
require_once 'Base.class.php';

class dao_coleta extends dao_base
{
    public function salvar()
    {
        return $this->inserir("
            INSERT INTO coleta (
                id_lagoa
                , id_ponto_amostral
                , id_categoria
                , data
                , tipo_periodo
                , profundidade     
            ) VALUES (
                :id_lagoa            
                , :id_ponto_amostral 
                , :id_categoria      
                , :data              
                , :tipo_periodo      
                , :profundidade      
            )
            ", array(
                ':id_lagoa'          => $this->model->getLagoa()->getId(),
                ':id_ponto_amostral' => $this->model->getPontoAmostral()->getId(),
                ':id_categoria'      => $this->model->getCategoria()->getId(),
                ':data'              => $this->model->getData(),
                ':tipo_periodo'      => $this->model->getTipoPeriodo(),
                ':profundidade'      => $this->model->getProfundidade()
            )
        );
    }

    public function buscarId(array $parametros)
    {
        $idLagoa         = $parametros[0];
        $idPontoAmostral = $parametros[1];
        $idCategoria     = $parametros[2];
        $data            = $parametros[3];
        $tipoPeriodo     = $parametros[4];
        $profundidade    = $parametros[5];

        $result = $this->recuperar("
            SELECT id_coleta FROM coleta
            WHERE id_lagoa = :id_lagoa          
                AND id_ponto_amostral = :id_ponto_amostral 
                AND id_categoria = :id_categoria      
                AND data = :data              
                AND tipo_periodo = :tipo_periodo      
                AND FORMAT(profundidade, 1) = FORMAT(:profundidade, 1)          
            ", array(
                'id_lagoa' => $idLagoa,          
                'id_ponto_amostral' => $idPontoAmostral, 
                'id_categoria' => $idCategoria,      
                'data' => $data,              
                'tipo_periodo' => $tipoPeriodo,      
                'profundidade' => $profundidade          
            )
        );

        if (empty($result)) {
            return FALSE;
        }

        return $result['id_coleta'];
    }
}

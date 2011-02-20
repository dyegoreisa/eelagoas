<?php
require_once 'Base.class.php';

class dao_coletaParametroEspecie extends dao_base
{
    public function salvar() 
    {   
        return $this->inserir("
            INSERT INTO coleta_parametro_especie (
                id_coleta_parametro        
                , id_especie                 
                , quantidade
            ) VALUES (
                :id_coleta_parametro        
                , :id_especie                 
                , :quantidade
            )
            ", array(
                ':id_coleta_parametro' => $this->model->getColetaParametro()->getId(),
                ':id_especie'          => $this->model->getEspecie()->getId(),
                ':quantidade'          => $this->model->getQuantidade()
            )
        );
    }

    public function buscarId(array $parametros)
    {
        $idColetaParametro = $parametros[0];
        $idEspecie         = $parametros[1];

        $result = $this->recuperar("
            SELECT id_coleta_parametro_especie FROM coleta_parametro_especie
            WHERE id_coleta_parametro = :id_coleta_parametro
                AND id_especie = :id_especie
            ", array(
                ':id_coleta_parametro' => $idColetaParametro,
                ':id_especie' => $idEspecie
            )
        );

        if (empty($result)) {
            return FALSE;
        }

        return $result['id_coleta_parametro_especie'];
    }
}

<?php
require_once 'Base.class.php';

class dao_coletaParametro extends dao_base
{
    public function salvar() 
    {   
        return $this->inserir("
            INSERT INTO coleta_parametro (
                id_coleta
                , id_parametro
                , valor
            ) VALUES (
                :id_coleta
                , :id_parametro
                , :valor
            )
            ", array(
                ':id_coleta'     => $this->model->getColeta()->getId(),
                ':id_parametro'  => $this->model->getParametro()->getId(),
                ':valor'         => $this->model->getValor()
            )
        );
    }

    public function buscarId(array $parametros)
    {
        $idColeta    = $parametros[0];
        $idParametro = $parametros[1];

        $result = $this->recuperar("
            SELECT id_coleta_parametro FROM coleta_parametro
            WHERE id_coleta = :id_coleta
                AND id_parametro = :id_parametro
            ", array(
                ':id_coleta'    => $idColeta,
                ':id_parametro' => $idParametro
            )
        );

        if (empty($result)) {
            return FALSE;
        }

        return $result['id_coleta_parametro'];
    }
}

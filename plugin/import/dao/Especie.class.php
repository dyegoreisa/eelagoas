<?php
require_once 'Base.class.php';

class dao_especie extends dao_base
{
    public function salvar()
    {
        return $this->inserir("
            INSERT INTO especie (
                id_parametro
                , nome
            ) VALUES (
                :id_parametro
                , :nome
            )
            ", array(
                ':id_parametro' => $this->model->getParametro()->getId(),
                ':nome'         => $this->model->getNome()
            )
        );
    }

    public function buscarId(array $parametros)
    {
        $idParametro = $parametros[0];
        $nome        = $parametros[1];

        $result = $this->recuperar("
            SELECT id_especie FROM especie
            WHERE id_parametro = :id_parametro
                AND nome = :nome
            ", array(
                ':id_parametro' => $idParametro,
                ':nome'         => $nome
            )
        );

        if (empty($result)) {
            return FALSE;
        }

        return $result['id_especie'];
    }
}

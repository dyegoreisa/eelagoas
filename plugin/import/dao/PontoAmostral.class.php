<?php
require_once 'Base.class.php';

class dao_pontoAmostral extends dao_base
{
    public function salvar()
    {
        return $this->inserir("
            INSERT INTO ponto_amostral (
                id_lagoa
                , nome
            ) VALUES (
                :id_lagoa
                , :nome
            )
            ", array(
                ':id_lagoa' => $this->model->getLagoa()->getId(),
                ':nome'     => $this->model->getNome()
            )
        );
    }

    public function buscarId(array $parametros)
    {
        $idLagoa = $parametros[0];
        $nome    = $parametros[1];

        $result = $this->recuperar("
            SELECT id_ponto_amostral FROM ponto_amostral
            WHERE id_lagoa = :id_lagoa
                AND nome = :nome
            ", array(
                ':id_lagoa' => $idLagoa,
                ':nome'     => $nome
            )
        );

        if (empty($result)) {
            return FALSE;
        }

        return $result['id_ponto_amostral'];
    }
}

<?php
require_once 'Base.class.php';

class dao_lagoa extends dao_base
{
    public function salvar()
    {
        return $this->inserir("
            INSERT INTO lagoa (
                id_projeto
                , nome
            ) VALUES (
                :id_projeto
                , :nome
            )
            ", array(
                ':id_projeto' => $this->model->getIdProjeto(),
                ':nome'       => $this->model->getNome()
            )
        );
    }

    public function buscarId(array $parametros)
    {
        $idProjeto = $parametros[0];
        $nome      = $parametros[1];

        $result = $this->recuperar("
            SELECT id_lagoa FROM lagoa
            WHERE id_projeto = :id_projeto
                AND nome = :nome
            ", array(
                ':id_projeto' => $idProjeto,
                ':nome'       => $nome
            )
        );

        if (empty($result)) {
            return FALSE;
        }

        return $result['id_lagoa'];
    }
}

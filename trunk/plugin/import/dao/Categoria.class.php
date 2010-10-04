<?php
require_once 'Base.class.php';

class dao_categoria extends dao_base
{
    public function salvar()
    {
        return $this->inserir("
            INSERT INTO categoria (
                nome
                , e_perfil
            ) VALUES (
                :nome
                , :e_perfil
            )
            ", array(
                ':nome'     => $this->model->getNome(),
                ':e_perfil' => $this->model->getEPerfil()
            )
        );
    }

    public function buscarId(array $parametros)
    {
        $nome = $parametros[0];

        $result = $this->recuperar("
            SELECT id_categoria FROM categoria
            WHERE nome = :nome
            ", array(
                ':nome' => $nome
            )
        );

        if (empty($result)) {
            return FALSE;
        }

        return $result['id_categoria'];
    }
}


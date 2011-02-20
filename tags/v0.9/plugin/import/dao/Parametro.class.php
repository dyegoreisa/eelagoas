<?php
require_once 'Base.class.php';

class dao_parametro extends dao_base
{
    public function salvar()
    {
        return $this->inserir("
            INSERT INTO parametro (
                nome
                , composicao
            ) VALUES (
                :nome
                , :composicao
            )
            ", array(
                ':nome'       => $this->model->getNome(),
                ':composicao' => $this->model->getComposicao()
            )
        );
    }

    public function buscarId(array $parametros)
    {
        $nome = $parametros[0];

        $result = $this->recuperar("
            SELECT id_parametro FROM parametro
            WHERE nome = :nome
            ", array(
                ':nome' => $nome
            )
        );

        if (empty($result)) {
            return FALSE;
        }

        return $result['id_parametro'];
    }
}

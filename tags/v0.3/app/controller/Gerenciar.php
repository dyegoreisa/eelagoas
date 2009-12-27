<?php
interface Gerenciar
{
    public function editar( $id = false );
    
    public function salvar();
    
    public function listar();
    
    public function buscar( $dados = false );
    
    public function excluir( $id );
}
?>

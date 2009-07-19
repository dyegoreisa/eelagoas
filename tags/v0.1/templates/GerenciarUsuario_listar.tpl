<fieldset>
  <legend>Lista de usuarios</legend>
  <ul>
    {foreach from=$usuarios item=usuario}
      <li>
        <a href="{$dir}/GerenciarUsuario/editar/{$usuario.id_usuario}" alt="Altera usuario">[ A ]</a>
        <a href="{$dir}/GerenciarUsuario/excluir/{$usuario.id_usuario}" alt="Exclui usuario" class="excluir">[ E ]</a>
        {$usuario.login} - {$usuario.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

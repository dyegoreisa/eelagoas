<fieldset>
{include file=$submenu titulo='Lista de usu&aacute;rios'}
  <ul>
    {foreach from=$usuarios item=usuario}
      <li>
        <a href="{$dir}/GerenciarPermissao/editar/{$usuario.id_usuario}" alt="Altera permissoes">[ P ]</a>
        <a href="{$dir}/GerenciarUsuario/editar/{$usuario.id_usuario}" alt="Altera usuario">[ A ]</a>
        <a href="{$dir}/GerenciarUsuario/excluir/{$usuario.id_usuario}" alt="Exclui usuario" class="excluir">[ E ]</a>
        {$usuario.login} - {$usuario.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

<fieldset>
{include file=$submenu titulo='Lista de perfis'}
  <ul>
    {foreach from=$perfis item=perfil}
      <li>
        <a href="{$dir}/GerenciarPermissao/editar/{$perfil.id_perfil}" alt="Altera permissoes">[ P ]</a>
        <a href="{$dir}/GerenciarPerfil/editar/{$perfil.id_perfil}" alt="Altera perfil">[ A ]</a>
        <a href="{$dir}/GerenciarPerfil/excluir/{$perfil.id_perfil}" alt="Exclui perfil" class="excluir">[ E ]</a>
        {$perfil.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

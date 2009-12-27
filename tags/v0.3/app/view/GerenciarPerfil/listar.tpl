<fieldset>
{include file=$submenu titulo='Lista de perfis'}
  <ul>
    {foreach from=$perfis item=perfil}
      <li>
        {foreach from=$acoesPerfil item=acao}
            <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$perfil.id_perfil}" alt="{$acao.alt}" class="{$acao.class}">{$acao.texto}</a>
        {/foreach}
        {$perfil.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

<fieldset>
{include file=$submenu titulo='Lista de perfis'}
<div class="scroll_lista">
  <ul class="lista">
    {foreach from=$perfis item=perfil}
      <li class="{cycle values="par, impar"}">
        {assign var="tplPieace" value="acoes_lista.tpl"}
        {assign var="id" value=$perfil.id_perfil}
        {include file=$pieces$tplPieace}
        {$perfil.nome}
      </li>
    {/foreach}
  </ul>
</div>
</fieldset>

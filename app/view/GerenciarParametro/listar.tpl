<fieldset>
{include file=$submenu titulo='Lista de par&acirc;metros'}
<div class="scroll_lista">
  <ul class="lista">
    {foreach from=$parametros item=parametro}
      <li class="{cycle values="par, impar"}">
        {assign var="tplPieace" value="acoes_lista.tpl"}
        {assign var="id" value=$parametro.id_parametro}    {* Vai para o template acoes_lista.tpl *}
        {assign var="compara" value=$parametro.composicao} {* Vai para o template acoes_lista.tpl *}
        {include file=$pieces$tplPieace}
        {$parametro.nome}
      </li>
    {/foreach}
  </ul>
</div>
</fieldset>

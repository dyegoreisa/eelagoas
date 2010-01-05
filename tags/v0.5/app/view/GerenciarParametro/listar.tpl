<fieldset>
{include file=$submenu titulo='Lista de par&acirc;metros'}
  <ul class="lista">
    {foreach from=$parametros item=parametro}
      <li class="{cycle values="par, impar"}">
        {assign var="tplPieace" value="acoes_lista.tpl"}
        {assign var="id" value=$parametro.id_parametro}
        {include file=$pieces$tplPieace}
        {$parametro.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

<fieldset>
{include file=$submenu titulo='Lista de categorias'}
<div class="scroll_lista">
  <ul class="lista">
    {foreach from=$categorias item=categoria}
      <li class="{cycle values="par, impar"}">
        {assign var="tplPieace" value="acoes_lista.tpl"}
        {assign var="id" value=$categoria.id_categoria}
        {include file=$pieces$tplPieace}
        {$categoria.nome}
      </li>
    {/foreach}
  </ul>
</div>
</fieldset>

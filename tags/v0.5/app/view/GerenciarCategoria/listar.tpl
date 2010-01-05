<fieldset>
{include file=$submenu titulo='Lista de categorias'}
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
</fieldset>

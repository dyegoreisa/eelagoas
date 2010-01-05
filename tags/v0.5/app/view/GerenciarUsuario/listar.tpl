<fieldset>
{include file=$submenu titulo='Lista de usu&aacute;rios'}
  <ul class="lista">
    {foreach from=$usuarios item=usuario}
      <li class="{cycle values="par, impar"}">
        {assign var="tplPieace" value="acoes_lista.tpl"}
        {assign var="id" value=$usuario.id_usuario}
        {include file=$pieces$tplPieace}
        {$usuario.login} - {$usuario.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

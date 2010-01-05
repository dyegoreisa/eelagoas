<fieldset>
{include file=$submenu titulo='Lista de projetos'}
    <ul class="lista">
    {foreach from=$projetos item=projeto}
        <li class="{cycle values="par, impar"}">
            {assign var="tplPieace" value="acoes_lista.tpl"}
            {assign var="id" value=$projeto.id_projeto}
            {include file=$pieces$tplPieace}
            {$projeto.nome}
        </li>
    {/foreach}
    </ul>
</fieldset>

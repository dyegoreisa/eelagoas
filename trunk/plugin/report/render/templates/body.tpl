<table id="report">
    <tr class="header">
        {foreach from=$colunas key=chave item=coluna}
            <th class="algin_{$alinhamento.$chave}">{$coluna}</th>
        {/foreach}
    </tr>
    {foreach from=$dados item=dado}
    <tr bgcolor="{cycle values="#eeeeee,#d0d0d0"}">
        {foreach from=$colunas key=chave item=coluna}
            <td class="align_{$alinhamento.$chave}">{$dado.$chave}</td>
        {/foreach}
    </tr>
    {/foreach}
</table>

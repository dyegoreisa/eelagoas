<table id="report">
    <tr class="header">
        {foreach from=$colunas key=chave item=coluna}
            <th class="align_{$alinhamento.$chave}">{$coluna}</th>
        {/foreach}
    </tr>
    {foreach from=$dados item=dado}
    <tr class="{cycle values="linha1,linha2"}">
        {foreach from=$colunas key=chave item=coluna}
            <td class="align_{$alinhamento.$chave}">{$dado.$chave}</td>
        {/foreach}
    </tr>
    {/foreach}
</table>

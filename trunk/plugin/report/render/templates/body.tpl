<table id="report">
    <tr>
        {foreach from=$colunas item=coluna}
            <th>{$coluna}</th>
        {/foreach}
    </tr>
    {foreach from=$dados item=dado}
    <tr>
        {foreach from=$colunas key=chave item=coluna}
            <td>{$dado.$chave}</td>
        {/foreach}
    </tr>
    {/foreach}
</table>

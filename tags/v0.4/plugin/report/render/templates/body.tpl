<table id="report">
    <tr class="header">
        {foreach from=$colunas key=chave item=coluna}
            <th class="align_{$alinhamento.$chave}">{$coluna}</th>
        {/foreach}
    </tr>
    {foreach from=$dados item=dado}
    <tr class="{cycle values="linha1,linha2"}">
        {foreach from=$colunas key=chave item=coluna}
            <td class="align_{$alinhamento.$chave}">
                {$dado->$chave}
                {if $chave eq 'nome_parametro' and $dado->tabela neq ''}
                    <ul class="body">
                    {foreach from=$dado->getExtras() item=extra}
                        <li>{$extra->descricao} - {$extra->nome}</li>
                    {/foreach}
                    </ul>
                {/if}

                {if $chave eq 'nome_categoria' and $dado->$chave eq 'Perfil'}
                    <ul class="body">
                        <li>{$dado->nome_categoria_extra} - {$dado->valor_categoria_extra}</li>
                    </ul>
                {/if}
            </td>
        {/foreach}
    </tr>
    {/foreach}
</table>

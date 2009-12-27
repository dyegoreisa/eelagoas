<fieldset>
    <legend>Lista de coletas da lagoa {$lagoa.nome}</legend>
    <ul>
    {foreach from=$coletas item=coleta}
        <li>
            {foreach from=$acoesColeta item=acao}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$coleta.id_coleta}" alt="{$acao.alt}" class="{$acao.class}">{$acao.texto}</a>
            {/foreach}
            {$coleta.tipo_periodo} - 
            {if $coleta.tipo_periodo eq 'mensal'}
                {$coleta.data|date_format:"%m/%Y %H"}h
            {else}
                {$coleta.data|date_format:"%d/%m/%Y %H"}h
            {/if}
        </li>
    {/foreach}
    </ul>
</fieldset>

<fieldset>
    <legend>Lista de coletas da lagoa {$lagoa.nome}</legend>
    <ul class="lista">
    {foreach from=$coletas item=coleta}
        <li class="{cycle values="par, impar"}">
            {foreach from=$acoesLista item=acao}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$coleta.id_coleta}" alt="{$acao.alt}" class="{$acao.class}"><img src="{$site}/images/{$acao.icone}" alt="{$acao.texto}" border="0"/></a>
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

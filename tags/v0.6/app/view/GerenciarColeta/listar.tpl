<fieldset>
    <legend>Lista de coletas da lagoa {$lagoa.nome}</legend>
    <ul class="lista">
    {foreach from=$coletas item=coleta}
        <li class="{cycle values="par, impar"}">
            {foreach from=$acoesLista item=acao}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$coleta.id_coleta}" alt="{$acao.alt}" title="{$acao.alt}" class="{$acao.class}"><img src="{$site}/images/{$acao.icone}" alt="{$acao.texto}" title="{$acao.alt}" border="0"/></a>
            {/foreach}
            {$coleta.tipo_periodo} - 
            {if $coleta.tipo_periodo eq 'mensal'}
                {$coleta.data|date_format:"%m/%Y %H"}h
            {else}
                {$coleta.data|date_format:"%d/%m/%Y %H"}h
            {/if} - 
            {$coleta.ponto_amostral} - 
            {$coleta.categoria}
        </li>
    {/foreach}
    </ul>
</fieldset>

{foreach from=$acoesLista item=acao}
    {if isset($acao.compara.valor) and $acao.compara.valor eq $compara}
        <img src="{$site}/images/{$acao.compara.icone2}" alt="{$acao.texto}" border="0"/>
    {else}
        <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$acao.param}{$id}" alt="{$acao.alt}" class="{$acao.class}" title="{$acao.alt}"><img src="{$site}/images/{$acao.icone}" alt="{$acao.texto}" border="0"/></a>
    {/if}
{/foreach}

{foreach from=$acoesLista item=acao}
    <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$acao.param}{$id}" alt="{$acao.alt}" class="{$acao.class}" title="{$acao.alt}"><img src="{$site}/images/{$acao.icone}" alt="{$acao.texto}" border="0"/></a>
{/foreach}

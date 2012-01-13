<fieldset>
{include file=$submenu titulo='Lista de pontos amostrais'}
<div class="scroll_lista">
    <ul class="lista">
    {foreach from=$pontosAmostrais item=pontoAmostral}
        <li class="{cycle values="par, impar"}">
            {foreach from=$acoesLista item=acao}
                {if $acao.modulo eq 'GerenciarLagoa'}
                    <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$pontoAmostral.id_lagoa}" alt="{$acao.alt}" title="{$acao.alt}" class="{$acao.class}">[_{$pontoAmostral.nome_lagoa}_]</a>
                {else}
                    <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$pontoAmostral.id_ponto_amostral}" alt="{$acao.alt}" title="{$acao.alt}" class="{$acao.class}"><img src="{$site}/images/{$acao.icone}" alt="{$acao.texto}" border="0"/></a>
                {/if}
            {/foreach}
            {$pontoAmostral.nome}
        </li>
    {/foreach}
    </ul>
</div>
</fieldset>

{if $mensagem neq ''}
    <p>{$mensagem}</p>
{/if}
<fieldset>
{include file=$submenu titulo='Lista de esp&eacute;cies'}
  <ul class="lista">
    {foreach from=$especies item=especie}
      <li class="{cycle values="par, impar"}">
        {foreach from=$acoesLista item=acao}
            {if $acao.modulo eq 'GerenciarParametro'}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$especie.id_parametro}" alt="{$acao.alt}" >[ {$especie.nome_parametro} ]</a>
            {else}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$especie.id_especie}" alt="{$acao.alt}" class="{$acao.class}"><img src="{$site}/images/{$acao.icone}" alt="{$acao.texto}" border="0"/></a>
            {/if}
        {/foreach}
        {$especie.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

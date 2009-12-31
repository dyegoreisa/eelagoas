<fieldset>
{include file=$submenu titulo='Lista de lagoas'}
  <ul class="lista">
    {foreach from=$lagoas item=lagoa}
      <li class="{cycle values="par, impar"}">
        {foreach from=$acoesLista item=acao}
            {if $acao.modulo eq 'GerenciarProjeto'}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$lagoa.id_projeto}" alt="{$acao.alt}" class="{$acao.class}">[_{$lagoa.nome_projeto}_]</a>
            {else}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$acao.param}{$lagoa.id_lagoa}" alt="{$acao.alt}" class="{$acao.class}"><img src="{$site}/images/{$acao.icone}" alt="{$acao.texto}" border="0"/></a>
            {/if}
        {/foreach}
        {$lagoa.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

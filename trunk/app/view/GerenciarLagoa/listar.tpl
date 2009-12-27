<fieldset>
{include file=$submenu titulo='Lista de lagoas'}
  <ul>
    {foreach from=$lagoas item=lagoa}
      <li>
        {foreach from=$acoesLagoa item=acao}
            {if $acao.modulo eq 'GerenciarProjeto'}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$lagoa.id_projeto}" alt="{$acao.alt}" class="{$acao.class}">[_{$lagoa.nome_projeto}_]</a>
            {else}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$acao.param}{$lagoa.id_lagoa}" alt="{$acao.alt}" class="{$acao.class}">{$acao.texto}</a>
            {/if}
        {/foreach}
        {$lagoa.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

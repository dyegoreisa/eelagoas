<fieldset>
{assign var=titulo value='Lista de lagoas do projeto '}
{assign var=nome_projeto value=$lagoas.0.nome_projeto}
{include file=$submenu titulo=$titulo$nome_projeto}
  <ul>
    {foreach from=$lagoas item=lagoa}
      <li>
        {foreach from=$acoesLagoa item=acao}
            {if $acao.modulo neq 'GerenciarProjeto'}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$acao.param}{$lagoa.id_lagoa}" alt="{$acao.alt}" class="{$acao.class}">{$acao.texto}</a>
            {/if}
        {/foreach}
        {$lagoa.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

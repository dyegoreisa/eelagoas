{if $mensagem neq ''}
    <p>{$mensagem}</p>
{/if}
<fieldset>
{assign var=titulo value='Lista de lagoas do projeto '}
{include file=$submenu titulo=$titulo$nomeProjeto}
  <ul class="lista">
    {foreach from=$lagoas item=lagoa}
      <li class="{cycle values="par, impar"}">
        {foreach from=$acoesLista item=acao}
            {if $acao.modulo neq 'GerenciarProjeto'}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$acao.param}{$lagoa.id_lagoa}" alt="{$acao.alt}" class="{$acao.class}"><img src="{$site}/images/{$acao.icone}" alt="{$acao.texto}" border="0"/></a>
            {/if}
        {/foreach}
        {$lagoa.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

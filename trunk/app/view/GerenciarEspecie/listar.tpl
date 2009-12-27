<fieldset>
{include file=$submenu titulo='Lista de esp&eacute;cies'}
  <ul>
    {foreach from=$especies item=especie}
      <li>
        {foreach from=$acoesEspecie item=acao}
            {if $acao.modulo eq 'GerenciarParametro'}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$especie.id_parametro}" alt="{$acao.alt}" >[ {$especie.nome_parametro} ]</a>
            {else}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$especie.id_especie}" alt="{$acao.alt}" class="{$acao.class}">{$acao.texto}</a>
            {/if}
        {/foreach}
        {$especie.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

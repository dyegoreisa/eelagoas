<fieldset>
{include file=$submenu titulo='Lista de par&acirc;metros'}
  <ul>
    {foreach from=$parametros item=parametro}
      <li>
        {foreach from=$acoesParametro item=acao}
            <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$parametro.id_parametro}" alt="{$acao.alt}" class="{$acao.class}">{$acao.texto}</a>
        {/foreach}
        {$parametro.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

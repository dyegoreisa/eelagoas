<fieldset>
  <legend>Lista de pontos amostrais {$lagoa.nome}</legend>
  <ul>
    {foreach from=$pontosAmostrais item=pontoAmostral}
      <li>
        {foreach from=$acoesPontoAmostral item=acao}
            <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$pontoAmostral.id_ponto_amostral}" alt="{$acao.alt}" class="{$acao.class}">{$acao.texto}</a>
        {/foreach}
        {$pontoAmostral.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

<fieldset>
  <legend>Lista de pontos amostrais {$lagoa.nome}</legend>
  <ul>
    {foreach from=$pontosAmostrais item=pontoAmostral}
      <li>
        <a href="{$dir}/GerenciarPontoAmostral/editar/{$pontoAmostral.id_ponto_amostral}" alt="Altera pontoAmostral">[ A ]</a>
        <a href="{$dir}/GerenciarPontoAmostral/excluir/{$pontoAmostral.id_ponto_amostral}" alt="Exclui pontoAmostral" class="excluir">[ E ]</a>
        {$pontoAmostral.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

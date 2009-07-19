<fieldset>
  <legend>Lista de lagoas</legend>
  <ul>
    {foreach from=$lagoas item=lagoa}
      <li>
        <a href="{$dir}/GerenciarColeta/buscar/{$lagoa.id_lagoa}" alt="Lista coletas">[ C ]</a>
        <a href="{$dir}/GerenciarPontoAmostral/buscar/{$lagoa.id_lagoa}" alt="Lista pontos amostrais">[ P ]</a>
        <a href="{$dir}/GerenciarLagoa/editar/{$lagoa.id_lagoa}" alt="Altera lagoa">[ A ]</a>
        <a href="{$dir}/GerenciarLagoa/excluir/{$lagoa.id_lagoa}" alt="Exclui lagoa" class="excluir">[ E ]</a>
        {$lagoa.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

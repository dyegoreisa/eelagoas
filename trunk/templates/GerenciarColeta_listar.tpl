<fieldset>
  <legend>Lista de coletas da lagoa {$lagoa.nome}</legend>
  <ul>
  {foreach from=$coletas item=coleta}
    <li>
      <a href="{$dir}/GerenciarColeta/editar/{$coleta.id_coleta}">[ A ]</a>
      <a href="{$dir}/GerenciarColeta/excluir/{$coleta.id_coleta}" alt="Deseja excluir o registro?" class="excluir">[ E ]</a>
      {$coleta.data|date_format:"%m/%Y"}
    </li>
  {/foreach}
  </ul>
</fieldset>

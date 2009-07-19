<fieldset>
  <legend>Lista de coletas da lagoa {$lagoa.nome}</legend>
  {foreach from=$coletas item=coleta}
    <li>
      <a href="{$dir}/GerenciarColeta/editar/{$coleta.id_coleta}">[ A ]</a>
      <a href="{$dir}/GerenciarColeta/excluir/{$coleta.id_coleta}">[ E ]</a>
      {$coleta.data}
    </li>
  {/foreach}
</fieldset>

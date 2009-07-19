<fieldset>
  <legend>Lista de parametros</legend>
  <ul>
    {foreach from=$parametros item=parametro}
      <li>
        <a href="{$dir}/GerenciarParametro/editar/{$parametro.id_parametro}" alt="Altera parametro">[ A ]</a>
        <a href="{$dir}/GerenciarParametro/excluir/{$parametro.id_parametro}" alt="Exclui parametro" class="excluir">[ E ]</a>
        {$parametro.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

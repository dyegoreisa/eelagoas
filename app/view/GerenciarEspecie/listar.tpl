<fieldset>
  <legend>Lista de especies</legend>
  <ul>
    {foreach from=$especies item=especie}
      <li>
        <a href="{$dir}/GerenciarEspecie/editar/{$especie.id_especie}" alt="Altera especie">[ A ]</a>
        <a href="{$dir}/GerenciarEspecie/excluir/{$especie.id_especie}" alt="Exclui especie" class="excluir">[ E ]</a>
        {$especie.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

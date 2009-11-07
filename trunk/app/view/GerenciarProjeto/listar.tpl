<fieldset>
  <legend>Lista de projetos</legend>
  <ul>
    {foreach from=$projetos item=projeto}
      <li>
        <a href="{$dir}/GerenciarProjeto/editar/{$projeto.id_projeto}" alt="Altera projeto">[ A ]</a>
        <a href="{$dir}/GerenciarProjeto/excluir/{$projeto.id_projeto}" alt="Exclui projeto" class="excluir">[ E ]</a>
        {$projeto.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

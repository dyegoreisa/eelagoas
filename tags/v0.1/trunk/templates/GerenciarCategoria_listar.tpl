<fieldset>
  <legend>Lista de categorias</legend>
  <ul>
    {foreach from=$categorias item=categoria}
      <li>
        <a href="{$dir}/GerenciarCategoria/editar/{$categoria.id_categoria}" alt="Altera categoria">[ A ]</a>
        <a href="{$dir}/GerenciarCategoria/excluir/{$categoria.id_categoria}" alt="Exclui categoria" class="excluir">[ E ]</a>
        {$categoria.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

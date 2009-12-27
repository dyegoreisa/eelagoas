<fieldset>
{include file=$submenu titulo='Lista de categorias'}
  <ul>
    {foreach from=$categorias item=categoria}
      <li>
        {foreach from=$acoesCategoria item=acao}
            <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$categoria.id_categoria}" alt="{$acao.alt}" class="{$acao.class}">{$acao.texto}</a>
        {/foreach}
        {$categoria.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

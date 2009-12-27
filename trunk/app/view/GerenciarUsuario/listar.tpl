<fieldset>
{include file=$submenu titulo='Lista de usu&aacute;rios'}
  <ul>
    {foreach from=$usuarios item=usuario}
      <li>
        {foreach from=$acoesUsuario item=acao}
            <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$usuario.id_usuario}" alt="{$acao.alt}" class="{$acao.class}">{$acao.texto}</a>
        {/foreach}
        {$usuario.login} - {$usuario.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

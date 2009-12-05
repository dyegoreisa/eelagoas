<fieldset>
{include file=$submenu titulo='Lista de lagoas'}
  <ul>
    {foreach from=$lagoas item=lagoa}
      <li>
        <a href="{$dir}/GerenciarColeta/buscar/id_lagoa/{$lagoa.id_lagoa}" alt="Lista coletas">[ C ]</a>
        <a href="{$dir}/GerenciarPontoAmostral/buscar/id_lagoa/{$lagoa.id_lagoa}" alt="Lista pontos amostrais">[ P ]</a>
        <a href="{$dir}/GerenciarLagoa/editar/{$lagoa.id_lagoa}" alt="Altera lagoa">[ A ]</a>
        <a href="{$dir}/GerenciarLagoa/excluir/{$lagoa.id_lagoa}" alt="Exclui lagoa" class="excluir">[ E ]</a>
        <a href="{$dir}/GerenciarProjeto/editar/{$lagoa.id_projeto}" alt="Exclui lagoa">[ {$lagoa.nome_projeto} ]</a>
        {$lagoa.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

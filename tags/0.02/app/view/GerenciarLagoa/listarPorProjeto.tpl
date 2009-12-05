<fieldset>
{assign var=titulo value='Lista de lagoas do projeto '}
{assign var=nome_projeto value=$lagoas.0.nome_projeto}
{include file=$submenu titulo=$titulo$nome_projeto}
  <ul>
    {foreach from=$lagoas item=lagoa}
      <li>
        <a href="{$dir}/GerenciarColeta/buscar/{$lagoa.id_lagoa}/id_lagoa" alt="Lista coletas">[ C ]</a>
        <a href="{$dir}/GerenciarPontoAmostral/buscar/id_lagoa/{$lagoa.id_lagoa}" alt="Lista pontos amostrais">[ P ]</a>
        <a href="{$dir}/GerenciarLagoa/editar/{$lagoa.id_lagoa}" alt="Altera lagoa">[ A ]</a>
        <a href="{$dir}/GerenciarLagoa/excluir/{$lagoa.id_lagoa}" alt="Exclui lagoa" class="excluir">[ E ]</a>
        {$lagoa.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

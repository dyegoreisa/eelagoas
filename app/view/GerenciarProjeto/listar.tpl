<fieldset>
{include file=$submenu titulo='Lista de projetos'}
    <ul>
    {foreach from=$projetos item=projeto}
        <li>
            <a href="{$dir}/GerenciarLagoa/listar/{$projeto.id_projeto}" alt="Listar lagoas">[ L ]</a>
            <a href="{$dir}/GerenciarProjeto/editar/{$projeto.id_projeto}" alt="Altera projeto">[ A ]</a>
            <a href="{$dir}/GerenciarProjeto/excluir/{$projeto.id_projeto}" alt="Exclui projeto" class="excluir">[ E ]</a>
            {$projeto.nome}
        </li>
    {/foreach}
    </ul>
</fieldset>

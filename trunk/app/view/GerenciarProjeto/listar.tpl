<fieldset>
{include file=$submenu titulo='Lista de projetos'}
    <ul>
    {foreach from=$projetos item=projeto}
        <li>
            {foreach from=$acoesProjeto item=acao}
                <a href="{$dir}/{$acao.modulo}/{$acao.metodo}/{$projeto.id_projeto}" alt="{$acao.alt}" class="{$acao.class}">{$acao.texto}</a>
            {/foreach}
            {$projeto.nome}
        </li>
    {/foreach}
    </ul>
</fieldset>

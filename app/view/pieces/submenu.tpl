<legend>{$titulo}:&nbsp;&nbsp;
    <a href="{$site}/index.php/{$modulo}/editar">Cadastrar</a> | 
    <a href="{$site}/index.php/{$modulo}/buscar">Buscar</a> | 
    <a href="{$site}/index.php/{$modulo}/listar">Listar</a>
    {foreach from=$linksSubMenu item=link}
        | <a href="{$site}/index.php/{$link.modulo}/{$link.metodo}">{$link.texto}</a>
    {/foreach}
</legend>

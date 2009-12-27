<legend>{$titulo}:&nbsp;&nbsp;
    {foreach from=$subMenu item=link}
        [ <a href="{$site}/index.php/{$modulo}/{$link.metodo}">{$link.texto}</a> ]
    {/foreach}
    {foreach from=$linksSubMenu item=link}
        [ <a href="{$site}/index.php/{$link.modulo}/{$link.metodo}">{$link.texto}</a> ]
    {/foreach}
</legend>

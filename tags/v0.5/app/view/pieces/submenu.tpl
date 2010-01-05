<legend>{$titulo}:&nbsp;&nbsp;
    {foreach from=$subMenu item=link}
        [ <a href="{$site}/index.php/{$modulo}/{$link.metodo}" title="{$link.texto}"><img src="{$site}/images/{$link.icone}" alt="{$link.texto}" border="0"/>{$link.texto}</a> ]
    {/foreach}
    {foreach from=$linksSubMenu item=link}
        [ <a href="{$site}/index.php/{$link.modulo}/{$link.metodo}" title="{$link.texto}"><img src="{$site}/images/{$link.icone}" alt="{$link.texto}" border="0"/>{$link.texto}</a> ]
    {/foreach}
</legend>

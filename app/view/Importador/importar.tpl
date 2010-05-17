{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
{if $erro eq 'erro'}
    [ <a href="{$site}/index.php/Importador/inserir">Voltar</a> ] 
{else}
    <fieldset>
        <legend>Importar Excel</legend>

        <p>Dados importados corretamente.</p>

    </fieldset>

{/if}


<fieldset>
{include file=$submenu titulo='Buscar ponto amostral'}
  {if $mensagem neq ""}
    <p>{$mensagem}</p>
  {/if}
  <form action="" method="GET">
    <input type="text" name="dados">
    <input type="submit" value="Buscar">
  </form>
</fieldset>

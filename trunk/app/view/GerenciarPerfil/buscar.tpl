<fieldset>
{include file=$submenu titulo='Buscar Perfil'}
  {if $mensagem neq ""}
    <p>{$mensagem}</p>
  {/if}
  <form action="" method="GET">
    <input type="text" name="dados">
    <input type="submit" value="Buscar">
  </form>
</fieldset>

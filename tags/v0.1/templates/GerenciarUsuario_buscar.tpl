<fieldset>
  <legend>Buscar Usuario</legend>
  {if $msg neq ""}
    <p>{$msg}</p>
  {/if}
  <form action="" method="GET">
    <input type="text" name="dados">
    <input type="submit" value="Buscar">
  </form>
</fieldset>

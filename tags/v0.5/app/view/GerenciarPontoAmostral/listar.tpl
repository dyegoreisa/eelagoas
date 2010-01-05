<fieldset>
  <legend>Lista de pontos amostrais {$lagoa.nome}</legend>
  <ul class="lista">
    {foreach from=$pontosAmostrais item=pontoAmostral}
      <li class="{cycle values="par, impar"}">
        {assign var="tplPieace" value="acoes_lista.tpl"}
        {assign var="id" value=$pontoAmostral.id_ponto_amostral}
        {include file=$pieces$tplPieace}
        {$pontoAmostral.nome}
      </li>
    {/foreach}
  </ul>
</fieldset>

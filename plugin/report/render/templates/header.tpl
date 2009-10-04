<html>
<head>
<title>
    {$barra_titulo}
</title>
<link rel="stylesheet" type="text/css" href="/eelagoas/plugin/report/render/css/report.css" />
<head>
<body>
<img src="{$logo}" id="logo">
<div id="header">
    <p>{$nome_relatorio}</p>
    <p>Emitido por: {$usuario}</p>
    <p>Emiss&atilde;o: {$data_emissao}</p>
</div>

<div id="filter">
    <ul>
    {foreach from=$filtros item=filtro}
            <li><b>{$filtro.field}:</b>  {$filtro.value}</li>
    {/foreach}
    </ul>
</div>
<p id="total">Total de registros impressos: {$total_linhas}</p>

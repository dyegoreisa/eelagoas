<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
    <h2>{$nome_relatorio}</h2>
    <p><b>Emitido por:</b> {$usuario}</p>
    <p><b>Emiss&atilde;o:</b> {$data_emissao}</p>
</div>

<div id="filter">
    <ul>
    {foreach from=$filtros item=filtro}
            <li><b>{$filtro.field}:</b>  {$filtro.value}</li>
    {/foreach}
    </ul>
</div>
<p id="total">Total de registros impressos: {$total_linhas}</p>

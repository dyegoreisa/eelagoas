<?php

/**
 * Pasta principal do sistema 
 */
define('ROOT_FOLDER', '/eelagoas');

/**
 * Define a rota padrão
 */
define('D_ROUTE', '/Main/run');

/**
 * Define o modulo de sessao para logar
 */
define('LOGIN', '/Sessao/login/');

/**
 * Define os caminhos padrôes 
 */
define('R_DIR', ROOT_FOLDER . '/index.php');
define('R_SITE', 'http://' . $_SERVER['HTTP_HOST'] . ROOT_FOLDER);
//define('D_ABSOLUTE', '/home/linksimbolico/www' . ROOT_FOLDER);
define('D_ABSOLUTE', '/home/dyego/projetos' . ROOT_FOLDER);

/**
 * Define informações para acesso ao banco de dados
 */
/*
define('DB_DRIVER', 'mysql');
define('DB_HOST',   'mysql.linksimbolico.com.br');
define('DB_NAME',   'linksimbolico');
define('DB_USER',   'linksimbolico');
define('DB_PASSWD', 'banco123');
*/
//Configuração local
define('DB_DRIVER', 'mysql');
define('DB_HOST',   'localhost');
define('DB_NAME',   'eelagoas');
define('DB_USER',   'root');
define('DB_PASSWD', 'banco123');

/**
 * Define a localização da pasta de visualizações, modelos e controladores
 */
define('DIR_MODELS',      'app/models');
define('DIR_CONTROLLER',  'app/controller');
define('DIR_VIEW',        'app/view');

/**
 * Define o localização dos templates em pedaços 
 */
define('DIR_PIECES', 'pieces/');
define('ABSOLUTE_PIECES', D_ABSOLUTE . '/' . DIR_VIEW . '/' . DIR_PIECES);

/**
 * Define os diretórios do Smarty
 */
define('TEMPLATES', 'app/view');
define('COMPILED',  'compiled');
define('CONFIG',    'config');
define('CACHE',     '');

/**
 * Limite de linha por consulta
 */
define('LIMIT', 50 );

/**
 * Diretório da instalação de framework
 */
/*
define('SMARTY_TEMPLATE', '/home/linksimbolico/www/api/smarty/');
define('FPDF', '/home/linksimbolico/www/api/fpdf16/');
define('SPREADSHEET', '/home/linksimbolico/www/api/Spreadsheet/Excel/');
*/
define('SMARTY_TEMPLATE', '');
define('FPDF', '');
define('SPREADSHEET', '');

/**
 * Logos para os relatórios 
 */
define('LOGO_SITE', ROOT_FOLDER . '/images/logo_longa.png');
define('LOGO_HTML', ROOT_FOLDER . '/images/logo.jpg');
define('LOGO_PDF', 'images/logo.jpg');
define('LOGO_XLS', 'images/logo.bmp');

/**
 * Define se mostra mensagens de erros do PHP 
 */
//define('DISPLAY_ERRORS', 'off');
define('DISPLAY_ERRORS', 'on');
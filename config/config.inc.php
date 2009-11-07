<?php

/**
 * Define a rota padrão
 */
define('D_ROUTE', '/Main/run');

/**
 * Define o modulo de sessao para logar
 */
define('LOGIN', '/Sessao/login/');

/**
 * Define o caminho padrão 
 */
define('R_DIR', '/eelagoas/index.php');

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
// Configuração local
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

/**
 * Define os diretórios do Smarty
 */
define('TEMPLATES', 'templates');
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

define('SMARTY_TEMPLATE', '');
define('FPDF', '');
define('SPREADSHEET', '');
/*
define('SMARTY_TEMPLATE', '/home/linksimbolico/www/api/smarty/');
define('FPDF', '/home/linksimbolico/www/api/fpdf16/');
define('SPREADSHEET', '/home/linksimbolico/www/api/Spreadsheet/Excel/');
*/

/**
 * Logos para os relatórios 
 */
 define('LOGO_HTML', '/eelagoas/images/logo.jpg');
 define('LOGO_PDF', 'images/logo.jpg');
 define('LOGO_XLS', 'images/logo_menor.bmp');

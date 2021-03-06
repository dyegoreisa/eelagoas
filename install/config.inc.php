<?php
// O nome original do projeto é eelagoas

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
define('D_ABSOLUTE', $_SERVER['DOCUMENT_ROOT'] . ROOT_FOLDER);
define('PROC_D_ABSOLUTE', '/var/www' . ROOT_FOLDER);
define('PROC', ($_SERVER['DOCUMENT_ROOT'] !== NULL) ? PROC_D_ABSOLUTE . '/' : '');

/**
 * Define a localização da pasta de visualizações, modelos e controladores
 */
define('DIR_MODELS',      PROC . 'app/models');
define('DIR_CONTROLLER',  PROC . 'app/controller');
define('DIR_VIEW',        'app/view');

/**
 * Define o localização dos templates em pedaços 
 */
define('DIR_PIECES',      'pieces/');
define('ABSOLUTE_PIECES', D_ABSOLUTE . '/' . DIR_VIEW . '/' . DIR_PIECES);

/**
 * Define os diretórios do Smarty
 */
define('TEMPLATES', PROC . 'app/view');
define('COMPILED',  PROC . 'compiled');
define('CONFIG',    PROC . 'config');
define('CACHE',     PROC . '');

/**
 * Limite de linha por consulta
 */
define('LIMIT', 999999 );

/**
 * Diretório da instalação de framework
 */
define('SMARTY_TEMPLATE', PROC . 'api/smarty/');
define('FPDF',            PROC . 'api/fpdf16/');
define('SPREADSHEET',     PROC . 'api/Spreadsheet/Excel/');

/**
 * Logos para os relatórios 
 */
define('LOGO_SITE', ROOT_FOLDER . '/images/logo_longa.png');
define('LOGO_HTML', ROOT_FOLDER . '/images/logo.jpg');
define('LOGO_PDF', 'images/logo.jpg');
define('LOGO_XLS', 'images/logo.bmp');

/**
 * Define informações para acesso ao banco de dados
 */

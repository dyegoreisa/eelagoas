<?php
/**
 * Caminho do arquivo XML
 */
define('FILE_XML', '');

/**
 * Endereço do site
 */
define('SITE', 'http://' . $_SERVER['HTTP_HOST'] . ROOT_FOLDER);

/**
 * Caminho do diretórios para o smarty 
 */
define('TEMPLATE_DIR', PROC . 'plugin/report/render/templates');
define('COMPILE_DIR',  PROC . 'compiled');
define('CONFIG_DIR',   PROC . '');
define('CACHE_DIR',    PROC . '');


/**
 *  Logos
 */
define('REP_LOGO_HTML', LOGO_HTML);
define('REP_LOGO_PDF',  LOGO_PDF);
define('REP_LOGO_XLS',  LOGO_XLS);


define('DATA_DRIVER', DB_DRIVER);
define('DATA_HOST',   DB_HOST);
define('DATA_NAME',   DB_NAME);
define('DATA_USER',   DB_USER);
define('DATA_PASSWD', DB_PASSWD);

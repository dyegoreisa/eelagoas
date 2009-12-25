<?php
include 'debug.class.php';
require_once 'config/config.inc.php';
require_once 'lib/lib.inc.php';
require_once 'lib/Route.class.php';
require_once 'lib/Connection.class.php';
require_once 'lib/Connection.class.php';
require_once 'app/view/BaseView.class.php';
require_once 'app/view/Template.class.php';
require_once 'app/view/Mensagem.class.php';
require_once 'app/models/BaseModel.class.php';
require_once 'app/controller/BaseController.class.php';

// Plugins
require_once 'plugin/report/Report.class.php';
require_once 'plugin/acesso/Acesso.class.php';

ini_set('display_errors', DISPLAY_ERRORS);

loadModules(DIR_MODELS);
loadModules(DIR_CONTROLLER);

session_start();

$smarty = new Template();
$smarty->setRootDirectory(R_DIR);
$smarty->setLiveSite(R_SITE);
$smarty->setAbsolutePieces(ABSOLUTE_PIECES);
$smarty->setFooter();
$smarty->assign('idUsuario', $_SESSION[$_SESSION['SID']]['idUsuario']);
$smarty->assign('idPerfil', $_SESSION[$_SESSION['SID']]['idPerfil']);
$smarty->assign('nomeUsuario', $_SESSION[$_SESSION['SID']]['nomeUsuario']);
$smarty->assign('nomePerfil', $_SESSION[$_SESSION['SID']]['nomePerfil']);

$route = new Route();
$route->setRouteDefault(D_ROUTE);

if (session_id() != @$_SESSION['SID']) {
    $route->setRoute(LOGIN); 
    $smarty->setHeader('common/header_logoff.tpl');
} else {
    $smarty->setHeader('common/header_logon.tpl');
}

$route->prepare();

$acesso = new Acesso_Acesso();
try {
    $temAcesso = $acesso->perfilTemAcessoAoMetodo($_SESSION[$_SESSION['SID']]['idPerfil'], $route->moduleBase, $route->method);
    if ($temAcesso == 'N') {
        $route->setRoute("/Main/negado/{$_SESSION[$_SESSION['SID']]['idPerfil']}/{$route->moduleBase}/{$route->method}");
    } 
    $route->run($smarty);
} catch(Exception $e) {
    // TODO: Fazer esquema de log para não matar a aplicação
    die('Erro na aplicação: ' . $e->getMessage());
}

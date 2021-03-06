<?php
if (!is_file('config/instaled')) {
    header('Location: install/install01.php');
}

ini_set('display_errors', false);

//include 'lib/Debug.class.php';
require_once 'config/config.inc.php';
require_once 'lib/lib.inc.php';
require_once 'lib/Route.class.php';
require_once 'lib/Connection.class.php';
require_once 'lib/Permissao.class.php';
require_once 'lib/Menu.class.php';
require_once 'lib/BaseView.class.php';
require_once 'lib/BaseModel.class.php';
require_once 'lib/BaseController.class.php';
require_once 'app/view/Template.class.php';
require_once 'app/view/Mensagem.class.php';

// Plugins
require_once PROC . 'plugin/report/Report.class.php';
require_once PROC . 'plugin/import/ImportadorExcel.class.php';

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

if (session_id() != @$_SESSION['SID'] && empty($argv)) {
    $route->setRoute(LOGIN); 
    $smarty->setHeader('common/header_logoff.tpl');
} elseif (!empty($argv) && is_array($argv)) {
    array_shift($argv);
    foreach ($argv as $param) {
        $url .= '/'. $param; 
    }
    $route->setRoute($url); 
} else {
    $smarty->setHeader('common/header_logon.tpl');
}

$permissao = new Permissao();
$menu      = new Menu($permissao);

$smarty->assign('menu', $menu->getMenu(R_SITE . '/index.php'));

$route->prepare();
$route->setTemplate($smarty);

try {
    $temAcesso = $permissao->perfilTemAcessoAoMetodo($route->moduleBase, $route->method);
    if ($temAcesso == 'N') {
        $route->setRoute("/Main/negado/{$_SESSION[$_SESSION['SID']]['idPerfil']}/{$route->moduleBase}/{$route->method}");
    } 
    $route->run();
} catch(ReflectionException $re) {
    switch ($re->getCode()) {
        case '0':
            Mensagem::addErro('Url incorreta.');
            break;
        case '-1':
            Mensagem::addErro('Url incorreta.');
            break;
        default:
            Mensagem::addErro(latinToUTF('Na aplicação.'));
            break;
    }
    $route->setRoute('/Main/run');
    $route->run();
} catch(Exception $e) {
    Mensagem::addErro($e->getMessage());
    $route->setRoute('/Main/run');
    $route->run();
}

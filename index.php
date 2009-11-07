<?php
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

loadModules( DIR_MODELS );
loadModules( DIR_CONTROLLER );

session_start();

$smarty = new Template();
$smarty->setRootDirectory( R_DIR );
$smarty->setFooter();

$route = new Route();
$route->setRouteDefault( D_ROUTE );

if( session_id() != @$_SESSION['SID'] ) {
  $route->setRoute( LOGIN ); 
  $smarty->setHeader( 'common/header_logoff.tpl' );
} else {
  $smarty->setHeader( 'common/header_logon.tpl' );
}

$route->prepare();

$route->run( $smarty );

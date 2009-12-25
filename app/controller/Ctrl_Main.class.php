<?php
class Ctrl_Main extends BaseController {
    private $perfil;
    public function __construct() {
        parent::__construct();

        $this->perfil = new Perfil($this->getDBH());
    }

    public function run() {
        $this->getSmarty()->displayHBF("run.tpl");
    }

    public function negado($idPerfil, $modulo, $metodo)
    {
        $smarty = $this->getSmarty();

        $this->perfil->buscar($idPerfil);
        $perfil = $this->perfil->getData();
        
        $smarty->assign('perfil', $perfil);
        $smarty->assign('nomeModulo', preg_replace('/(?<=[a-z])(?=[A-Z])/', ' ', $modulo));
        $smarty->assign('nomeMetodo', $metodo);
        $smarty->displaySubMenuHBF('negado.tpl');
    }

}

<?php
class Ctrl_Relatorio extends BaseController
{
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function reportInterface()
    {
        $report = new Report();
        $report->setXML();

        $smarty = $this->getSmarty();
        $smarty->assign('reportInterface', $report->fetch());
        $smarty->displayHBF( 'interface.tpl' );
    }
}

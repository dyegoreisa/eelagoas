<?php
class Ctrl_Main extends BaseController {
  public function __construct() {
    parent::__construct();
  }

  public function run() {
    $this->getSmarty()->displayHBF("run.tpl");
  }

}

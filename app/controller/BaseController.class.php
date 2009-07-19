<?php
/**
 * BaseController 
 * 
 * @package 
 * @version   $id$
 * @copyright 2009 - Link Simbólico
 * @author    Dyego Reis de Azevedo <dyegoreisa@yahoo.com.br> 
 * @license   PHP Version 5.2 {@link http://www.php.net/license/}
 */
abstract class BaseController {
  /**
   * smarty 
   * 
   * @var mixed
   * @access protected
   */
  protected $smarty;

  /**
   * dbh 
   * 
   * @var mixed
   * @access protected
   */
  protected $dbh;

  /**
   * tpl 
   * 
   * @var mixed
   * @access protected
   */
  protected $tpl;

  /**
   * error 
   * 
   * @var mixed
   * @access protected
   */
  protected $error;

  /**
   * __construct 
   * 
   * @access protected
   * @return void
   */
  public function __construct() {
    $conn = new Connection();
    $conn->prepare( DB_DRIVER, DB_HOST, DB_NAME, DB_USER, DB_PASSWD ); 
    $conn->connect();

    $this->dbh = $conn->dbh;
  }

  /**
   * setSmarty 
   * 
   * @param mixed $smarty 
   * @access public
   * @return void
   */
  public function setSmarty( Template $smarty, $tpl ) {
    $this->smarty = $smarty;
    $this->smarty->setTpl( $tpl );
  } 

  /**
   * getSmarty 
   * 
   * @access public
   * @return void
   */
  public function getSmarty() {
    return $this->smarty;
  }

  /**
   * getDBH 
   * 
   * @access public
   * @return void
   */
  public function getDBH() {
    return $this->dbh;
  }

  /**
   * setTpl 
   * 
   * @param mixed $tpl 
   * @access public
   * @return void
   */
  public function setTpl( $tpl ) {
    $this->tpl = $tpl;
  }

  /**
   * getTpl 
   * 
   * @access public
   * @return void
   */
  public function getTpl() {
    return $this->tpl;
  }
  
  /**
   * setError 
   * 
   * @param Error $error 
   * @access public
   * @return void
   */
  public function setError( Error $error ) {
    $this->error;
  }

  /**
   * getError 
   * 
   * @access public
   * @return void
   */
  public function getError() {
    return $this->error;
  }

  /**
   * go
   * 
   * @param mixed $module 
   * @access public
   * @return void
   */
  public function go( $module ) {
    header("Location: " . R_DIR . $module);
  }

}

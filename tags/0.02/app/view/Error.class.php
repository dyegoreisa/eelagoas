<?php
/**
 * Error 
 * 
 * @uses BaseView
 * @package 
 * @version   $id$
 * @copyright 2009 - Link Simbólico
 * @author    Dyego Reis de Azevedo <dyegoreisa@yahoo.com.br> 
 * @license   PHP Version 5.2 {@link http://www.php.net/license/}
 */
class Error extends BaseView{
  /**
   * mensage 
   * 
   * @param mixed $msg 
   * @param string $type 
   * @access public
   * @return void
   */
  public function mensage( $msg, $type = 'ERROR' ) {
    switch( $type ) {
      case 'INFO':    $exit = false;  break;
      case 'WARNING': $exit = false;  break;
      case 'ERROR':   $exit = true;   break;
    }

    $this->assign( 'type', $type );
    $this->assign( 'msg', $msg );
    $this->displayHBF( 'error.tpl' );

    if( $exit ) exit;
  }
}

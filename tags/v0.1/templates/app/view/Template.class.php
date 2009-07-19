<?php

/**
 * Template 
 * 
 * @uses Smarty
 * @package 
 * @version   $id$
 * @copyright 2009 - Link Simbólico
 * @author    Dyego Reis de Azevedo <dyegoreisa@yahoo.com.br> 
 * @license   PHP Version 5.2 {@link http://www.php.net/license/}
 */
class Template extends BaseView{
  /**
   * directory 
   * 
   * @var mixed
   * @access protected
   */
  protected $directory;

  /**
   * __construct 
   * 
   * @access public
   * @return void
   */
  public function __construct () {
    $this->setDirectory( TEMPLATES, COMPILED, CONFIG, CACHE );
  }

  /**
   * setDirectory 
   * 
   * @param mixed $directory 
   * @access public
   * @return void
   */
  public function setRootDirectory( $directory ) {
    $this->directory = $directory;
    $this->assign( 'dir', $this->directory );
  }
}

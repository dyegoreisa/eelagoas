<?php
require_once SMARTY_TEMPLATE . 'Smarty.class.php';

/**
 * Template 
 * 
 * @uses Smarty
 * @abstract
 * @package 
 * @version     $id$
 * @copyright 2009 - Link Simbólico
 * @author        Dyego Reis de Azevedo <dyegoreisa@yahoo.com.br> 
 * @license     PHP Version 5.2 {@link http://www.php.net/license/}
 */
abstract class BaseView extends Smarty{
    /**
     * tpl 
     * 
     * @var mixed
     * @access protected
     */
    protected $tpl;

    /**
     * __construct 
     * 
     * @access public
     * @return void
     */
    public function __construct(){
        parent::Smarty();
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
     * setDirectory 
     * 
     * @param mixed $templates 
     * @param mixed $compiled 
     * @param mixed $config 
     * @param mixed $cache 
     * @access public
     * @return void
     */
    public function setDirectory( $templates, $compiled, $config, $cache ) {
        $this->template_dir = $templates;
        $this->compile_dir    = $compiled;
        $this->config_dir     = $config;
        $this->cache_dir        = $cache;
    }

    /**
     * setHeader 
     * 
     * @param mixed $header 
     * @access public
     * @return void
     */
    public function setHeader( $header ) {
        $this->header = $header;
    }

    /**
     * getHeader 
     * 
     * @access public
     * @return void
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * setFooter 
     * 
     * @param mixed $footer 
     * @access public
     * @return void
     */
    public function setFooter( $footer = 'common/footer.tpl' ) {
        $this->footer = $footer;
    }

    /**
     * getFooter 
     * 
     * @access public
     * @return void
     */
    public function getFooter() {
        return $this->footer;
    }

    /**
     * displayHBF 
     * 
     * @param mixed $body 
     * @access public
     * @return void
     */
    public function displayHBF( $body ) {
        $this->display( $this->getHeader() );
        $this->display( $this->getTpl() . '/' . $body );
        $this->display( $this->getFooter() );
    }

    /**
     * displayError 
     * 
     * @param mixed $body 
     * @access public
     * @return void
     */
    public function displayError() {
        $this->display( $this->getHeader() );
        $this->display( 'error.tpl' );
        $this->display( $this->getFooter() );
    }

    /**
     * displayTpl 
     * 
     * @param mixed $template 
     * @access public
     * @return void
     */
    public function displayTpl ( $template ) {
        $this->assign('tpl', $this->getTpl());
        $this->display( $this->getTpl() . '_' . $template );
    }

    /**
     * displayPiece 
     * 
     * @param mixed $template 
     * @access public
     * @return void
     */
    public function displayPiece ( $template ) {
        $this->display( DIR_PIECES . $template );
    }
}

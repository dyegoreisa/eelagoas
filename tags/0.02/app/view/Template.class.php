<?php

/**
 * Template 
 * 
 * @uses Smarty
 * @package 
 * @version     $id$
 * @copyright 2009 - Link Simbólico
 * @author        Dyego Reis de Azevedo <dyegoreisa@yahoo.com.br> 
 * @license     PHP Version 5.2 {@link http://www.php.net/license/}
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
     * liveSite 
     * 
     * @var mixed
     * @access protected
     */
    protected $liveSite;

    /**
     * absolutePieces 
     * 
     * @var mixed
     * @access protected
     */
    protected $absolutePieces;

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

    /**
     * setLiveSite 
     * 
     * @param mixed $liveSite 
     * @access public
     * @return void
     */
    public function setLiveSite($liveSite) {
        $this->liveSite = $liveSite;
        $this->assign('site', $this->liveSite);
    }

    /**
     * setAbsolutePieces 
     * 
     * @param mixed $absolutePieces 
     * @access public
     * @return void
     */
    public function setAbsolutePieces($absolutePieces) {
        $this->absolutePieces = $absolutePieces;
        $this->assign('pieces', $this->absolutePieces);
    }

}

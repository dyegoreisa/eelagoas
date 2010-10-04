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
        $this->showMensagem(Mensagem::fetch());
        $this->display( $this->getHeader() );
        $this->display( $this->getTpl() . '/' . $body );
        $this->display( $this->getFooter() );
    }

    public function displaySubMenuHBF($body, $icone = '') {
        $subMenu = array(
            array(
                'modulo' => $this->getTpl(),
                'metodo' => 'editar',
                'texto'  => 'Cadastrar',
                'icone'  => 'cadastrar.png'
            ),
            array(
                'modulo' => $this->getTpl(),
                'metodo' => 'buscar',
                'texto'  => 'Buscar',
                'icone'  => 'buscar.png'

            ),
            array(
                'modulo' => $this->getTpl(),
                'metodo' => 'listar',
                'texto'  => 'Listar',
                'icone'  => 'listar.png'

            )
        );
        $permissao = new Permissao();
        $this->assign('subMenu', $permissao->getListaPermitida($subMenu));
        $this->assign('modulo', $this->getTpl());
        $this->assign('submenu', ABSOLUTE_PIECES . '/submenu.tpl');
        $this->displayHBF($body);
    } 

    public function addSubMenuItem(array $itens)
    {
        $this->assign('linksSubMenu', $itens);
    }

    /**
     * displayError 
     * 
     * @param mixed $body 
     * @access public
     * @return void
     */
    public function displayError() {
        $this->showMensagem(Mensagem::fetch());
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
    public function displayPiece ($template, $ajax = false) {
        if ($ajax) {
            print mb_convert_encoding($this->fetch(DIR_PIECES . $template), 'UTF-8', 'ISO-8859-1');
        } else {
            $this->display( DIR_PIECES . $template );
        }
    }

    /**
     * fetchPiece 
     * 
     * @param mixed $template 
     * @access public
     * @return void
     */
    public function fetchPiece($template) {
        return $this->fetch(DIR_PIECES . $template);
    }

    /**
     * displayJson 
     * 
     * @param mixed $dados 
     * @access public
     * @return void
     */
    public function displayJson($dados) {
        $json = json_encode($dados);
        header("Content-Type: text/html; charset=iso-8859-1");
        header('X-JSON: ' . $json);
        print $json;
    }

    public function showMensagem($mensagem)
    {
        $this->assign('mensagem', $mensagem);
    }
    
    public function setIcone($icone)
    {
        $this->assign('icone', $icone);
    }
}

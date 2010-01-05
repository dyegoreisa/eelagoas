<?php
/**
 * Route 
 * 
 * @package 
 * @version     $id$
 * @copyright 2009 - Link Simbólico
 * @author        Dyego Reis de Azevedo <dyegoreisa@yahoo.com.br> 
 * @license     PHP Version 5.2 {@link http://www.php.net/license/}
 */
class Route {
    /**
     * url 
     * 
     * @var mixed
     * @access private
     */
    private $url;

    /**
     * urlDefault 
     * 
     * @var mixed
     * @access private
     */
    private $urlDefault;

    /**
     * module 
     * 
     * @var mixed
     * @access private
     */
    private $module;

    /**
     * moduleBase 
     * 
     * @var mixed
     * @access private
     */
    private $moduleBase;

    /**
     * method 
     * 
     * @var mixed
     * @access private
     */
    private $method;

    /**
     * params 
     * 
     * @var mixed
     * @access private
     */
    private $params;

    /**
     * smarty 
     * 
     * @var Template
     * @access private
     */
    private $smarty;

    /**
     * __construct 
     * 
     * @access public
     * @return void
     */
    public function __construct() {
        $this->url = @$_SERVER['PATH_INFO'];
    }

    /**
     * __set 
     *
     *  Seta valores das variáveis da classe
     * 
     * @param mixed $propriedade 
     * @param mixed $valor 
     * @access public
     * @return void
     */
    public function __set($propriedade, $valor)
    {
        if (isset($this->$propriedade)) {
            $this->$propriedade = $valor;
        } else {
            throw new Exception("ERROR SET: Propriedade '{$propriedade}' da classe 'Route' não existe.");
        }   
    }

    /**
     * __get 
     *
     *  Obtém valor das variáveis da classe
     * 
     * @param mixed $propriedade 
     * @access public
     * @return mixed
     */
    public function __get($propriedade)
    {
        if (isset($this->$propriedade)) {
            return $this->$propriedade;
        } else {
            throw new Exception("ERROR GET: Propriedade '{$propriedade}' da classe 'Route' não existe.");
        }
    }

    /**
     * prepare 
     *
     *    Trasnforma a rota de Model/method em variaveis
     *    para rodar aplicação
     * 
     * @access public
     * @return void
     */
    public function prepare() {
        if( !empty( $this->url ) ) {
            $url = $this->url;
        } elseif( !empty( $this->urlDefault ) ) {
            $url = $this->urlDefault;
        } else {
            user_error('Não foi definida nenhuma rota como default.');
            exit;
        }

        $pieces = explode('/', $url);
        $this->moduleBase = $pieces[1];
        $this->module = "Ctrl_{$pieces[1]}";
        $this->method = $pieces[2];

        if( isset( $pieces[3] ) ) {
            for( $i = 3; $i < count($pieces); $i++ ) {
                $this->params[$i-3] = $pieces[$i];
            }
        }
    }

    /**
     * run 
     *
     *    Roda a aplicação
     * 
     * @access public
     * @return void
     */
    public function run() {
        $this->prepare();
        $app = new $this->module;
        $app->setSmarty($this->smarty, $this->moduleBase);

        $refMethod = new ReflectionMethod($app, $this->method);
        if (is_array($this->params)) {
            $refMethod->invokeArgs($app, $this->params);
        } else {
            $refMethod->invoke($app);
        }


    }

    /**
     * setRoute 
     * 
     * @param mixed $url 
     * @access public
     * @return void
     */
    public function setRoute( $url ) {
        $this->url = $url; 
    }

    /**
     * setRouteDefault 
     * 
     * @param mixed $url 
     * @access public
     * @return void
     */
    public function setRouteDefault( $url ) {
        $this->urlDefault = $url;
    }

    /**
     * getModule 
     * 
     * @access public
     * @return string
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * getMethod 
     * 
     * @access public
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * getParams 
     * 
     * @access public
     * @return void
     */
    public function getParams() {
        $string = '';
        if( !empty( $this->params ) ) {
            $string = "'" . implode("', '", $this->params) . "'";
        }
        return $string;
    } 

    /**
     * setTemplate 
     * 
     * @param Template $smarty 
     * @access public
     * @return void
     */
    public function setTemplate(Template $smarty)
    {
        $this->smarty = $smarty;
    }
}

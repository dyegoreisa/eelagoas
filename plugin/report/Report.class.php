<?php
require_once 'config.inc.php';
require_once 'XML/XML.class.php';

class Report
{
    
    /**
     * Objeto XML para acesso e parse do arquivo
     * 
     * @var XML
     * @access private
     */
    private $xml;

    /**
     * Array de objetos field que são os campos no XML
     * 
     * @var array
     * @access private
     */
    private $fields;

    /**
     * Método construtor que inicia variáveis
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->xml = null;
        $this->fields = array();
    }

    /**
     * Seta o XML
     * 
     * @access public
     * @return void
     */
    public function setXML()
    {
        $this->xml = new XML();
        $this->xml->setFile(FILE_XML);
    }

    public function setFields(array $fields)
    {
        $this->fields = $fields;
        debug($this->fields);
    }

    /**
     * 
     * 
     * @access public
     * @return string 
     */
    public function fetch()
    {
        $this->xml->openFile();
        $this->setFields($this->xml->getFields());
        $this->xml->parse();
        return 'teste';
    }

}

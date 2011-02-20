<?php

require_once 'Relatorio.php';

class Ctrl_Relatorio extends BaseController implements Relatorio
{
    private $projeto;
    private $lagoa;
    private $pontoAmostral;
    private $categoria;
    private $parametro;
    private $usuario;

    public function __construct() 
    {
        parent::__construct();

        $dbh = $this->getDBH();

        $this->projeto       = new Projeto($dbh);
        $this->lagoa         = new Lagoa($dbh);
        $this->pontoAmostral = new PontoAmotral($dbh);
        $this->categoria     = new Categoria($dbh);
        $this->parametro     = new Parametro($dbh);
        $this->usuario       = new Usuario($dbh);
    }
    
    public function selecionar()
    {
        $smarty = $this->getSmarty();
        $smarty->assign( 'select_projeto', $this->projeto->listarSelectAssoc(array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        )) );
        $smarty->assign( 'select_categoria', $this->categoria->listarSelectAssoc(array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        )) );
        $smarty->assign( 'select_parametro', $this->parametro->listarSelectAssoc(array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        )) );
        $smarty->displayHBF( 'interface.tpl' );
    }

    private function gerarXmlDeFiltros(array $arrayFiltros, $arquivo) 
    {
        // versao do encoding xml
        $dom = new DOMDocument("1.0", "ISO-8859-1");

        // retirar os espacos em branco
        $dom->preserveWhiteSpace = false;

        // gerar o codigo
        $dom->formatOutput = true;

        // criando o nó principal
        $filtros = $dom->createElement("filtros");

        foreach ($arrayFiltros as $item => $valores) {
            if (is_array($valores)) {
                $filtro = $dom->createElement($item);

                foreach ($valores as $key => $val) {
                    $elemento = $dom->createElement("elemento");

                    $chave = $dom->createElement("chave", $key);
                    $valor = $dom->createElement("valor", $val);

                    $elemento->appendChild($chave);
                    $elemento->appendChild($valor);

                    $filtro->appendChild($elemento);
                }

                $filtros->appendChild($filtro);
            } else { 
                $filtro   = $dom->createElement($item);
                $valor    = $dom->createElement("valor", $valores);

                $filtro->appendChild($valor);

                $filtros->appendChild($filtro);
            }
        }

        $dom->appendChild($filtros);

        $dom->save('/tmp/' . $arquivo);
    }

    private function gerarArrayDeFiltros($arquivo)
    {
        $objFiltros = simplexml_load_file('/tmp/' . $arquivo); 

        foreach ($objFiltros as $filtro => $obj) {
            if (isset($obj->elemento) === true) {
                foreach ($obj->elemento as $elemento) {
                    $tmp[$elemento->chave . PHP_EOL + 0] = $elemento->valor . PHP_EOL + 0;
                }
            } else {
                $tmp = str_replace("\n", '', $obj->valor . PHP_EOL);
            }
            $filtros[$filtro] = $tmp;
        }

        return $filtros;
    }

    public function gerar()
    {   
        $arquivo = 'filtros_' . rand(1, 1000) . '.xml';

        $this->gerarXmlDeFiltros($_POST, $arquivo);

        $this->usuario->setId($_SESSION[$_SESSION['SID']]['idUsuario']);
        $this->usuario->pegar();

        $this->gerarWeb($arquivo, $this->usuario->getData('nome'));
    }

    private function gerarWeb($xml, $nomeUsuario)
    {
        $this->processarRelatorio($xml, $nomeUsuario, false);
    }

    public function gerarBackground($xml, $nomeUsuario)
    {
        $this->processarRelatorio($xml, $nomeUsuario, true);
    }

    /**
     * Executa processamento para gerar o relatório
     * 
     * @param string $xml 
     * @param string $nomeUsuario 
     * @param boolean $EArquivo - true para gerar arquivo e false para exibição na web
     * @access private
     * @return void
     */
    private function processarRelatorio($xml, $nomeUsuario, $EArquivo)
    {
        // Desabilita o Garbage Collection
        gc_disable();

        $filtros = $this->gerarArrayDeFiltros($xml);

        $tipoRelatorio = $filtros['tipo_relatorio'];
        $idProjetos    = (isset($filtros['projeto'])    && $filtros['projeto']    != '') ? implode(',', $filtros['projeto']) : '';
        $idLagoas      = (isset($filtros['lagoa'])      && $filtros['lagoa']      != '') ? implode(',', $filtros['lagoa'])   : '';
        $orientacao    = (isset($filtros['orientacao']) && $filtros['orientacao'] != '') ? $filtros['orientacao']             : 'L';
        $formato       = (isset($filtros['formato'])    && $filtros['formato']    != '') ? $filtros['formato']                : 'A4';

        $report = new Report(
            $nomeUsuario,
            $filtros,
            'Relatório'
        );

        $process = $report->getProcess();
        $process->setOrder('
            data, 
            nome_lagoa, 
            nome_ponto_amostral, 
            nome_categoria, 
            nome_parametro
        ');

        $report->addColumn('data',                'Data ',             'L', 1); 
        $report->addColumn('nome_projeto',        'Projeto ',          'L', 1); 
        $report->addColumn('nome_lagoa',          'Lagoa ',            'L', 1); 
        $report->addColumn('nome_ponto_amostral', 'Ponto Amostral ',   'L', 1); 
        $report->addColumn('nome_categoria',      'Categoria ',        'L', 1); 
        $report->addColumn('profundidade',        'Profundidade ',     'L', 1); 
        $report->addColumn('parametro',           'Par&acirc;metros ', 'C', 1); 

        switch ($tipoRelatorio) {
            case 'html':
                $report->setHtml();
                $render = $report->getRender();
                break;

            case 'pdf':
                $report->changeColumn('data',                'Width', 9);
                $report->changeColumn('nome_projeto',        'Width', 15);
                $report->changeColumn('nome_lagoa',          'Width', 9);
                $report->changeColumn('nome_ponto_amostral', 'Width', 10);
                $report->changeColumn('nome_categoria',      'Width', 10);
                $report->changeColumn('profundidade',        'Width', 9);
                $report->changeColumn('parametro',           'Text', mb_convert_encoding('Parâmetros ', 'latin1', 'UTF-8'));
                $report->setPdf($orientacao, $formato);
                
                $render = $report->getRender();
                break;

            case 'xls':
                $report->changeColumn('parametro', 'Text', 'Parâmetro');
                $report->setXls();
                $render = $report->getRender();
                break;

            default:
                die('Tipo de relatório não informado.');
        }

        $render->setLists(array(
            'projeto'        => $this->projeto->listarSelectAssoc(array('campo' => 'nome', 'ordem' => 'ASC')),
            'lagoa'          => $this->lagoa->listarSelectAssoc($idProjetos, array('campo' => 'nome', 'ordem' => 'ASC')),
            'categoria'      => $this->categoria->listarSelectAssoc(array('campo' => 'nome', 'ordem' => 'ASC')),
            'parametro'      => $this->parametro->listarSelectAssoc(array('campo' => 'nome', 'ordem' => 'ASC')),
            'ponto_amostral' => $this->pontoAmostral->listarSelectAssoc($idLagoas, array('campo' => 'nome', 'ordem' => 'ASC')),
            'tipo_periodo'   => array('diario' => 'Diario', 'mensal' => 'Mensal'),
        ));
        $render->render();
    }
}

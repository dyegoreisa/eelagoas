<?php

require_once SPREADSHEET . 'Reader.php';

// inclusão dos modelos de objetos
require_once 'model/Coleta.class.php';

// inclusão dos objetos de acesso aos dados
require_once 'dao/Excel.class.php';

class ImportadorExcel 
{
    private $file;
    private $data;

    public function __construct()
    {
        $this->data = new Spreadsheet_Excel_Reader();
        $this->data->setOutputEncoding('CP1251');
    }

    public function setFile($file)
    {
        $this->file = $file;
        //$this->data->setOutputEncoding('CPa25a');
        //$this->data->setDefaultFormat('%.2f');
        $this->data->setDefaultFormat(0);
        $this->data->setColumnFormat(1, 'd/m/Y H');
        $this->data->setColumnFormat(2, '@');
        $this->data->setColumnFormat(3, '@');
        $this->data->setColumnFormat(4, '0');
        $this->data->setColumnFormat(5, '@');
        $this->data->setColumnFormat(6, '0.0');

        $this->data->read($this->file);
    }

    public function getArrayData($linhas = NULL)
    {
        if (!isset($linhas)) {
            $linhas = $this->data->rowcount();
        } else {
            if ($linhas > $this->data->rowcount()) {
                $linhas = $this->data->rowcount();
            }
        }

        $dados = array();
        for ($i = 1; $i <= $linhas; $i++) {
            for ($j = 1; $j <= $this->data->colcount(); $j++) {
                if ($this->data->sheets[0]['cells'][$i][$j] !== '') {
                    $dados[$i][$j] = array('data' => $this->data->value($i, $j));
                } else {
                    $dados[$i][$j] = '';
                }
            }
        }

        $cabecalho[] = array_shift($dados);
        $cabecalho[] = array_shift($dados);
        $cabecalho[] = array_shift($dados);

        return array(
            'cabecalho' => $cabecalho,
            'dados'     => $dados,
            'excel'     => $this->data
        );
    }

    /**
     * Insere dados no banco de uma planilha excel
     * 
     * @param array $dados 
     * @access public
     * @return int - 0 para erro, 1 para Ok e 2 para alguns erros
     */
    public function insertData(array $dados) 
    {
        $daoExcel = new dao_excel($dados['id_projeto'], $dados['excel']);

        $coletas = $daoExcel->getColetas();

        $erro = 0;
        if (!empty($coletas)) {
            foreach ($coletas as $coleta) {
                if ($coleta->getId()) {
                    $coleta->salvarParametros();
                } else {
                    $erro++;
                }
            }
        }

        if (count($coletas) == $erro) {
            return 0; // Erro em todas as coletas
        }

        return 1;
    }
}
?>

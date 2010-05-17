<?php

require_once 'Importar.php';

class Ctrl_Importador extends BaseController implements Importar{

    public function __construct() 
    {
        parent::__construct();

        $dbh = $this->getDBH();

        $this->projeto = new Projeto($dbh);
    }

    public function selecionar()
    {
        $smarty = $this->getSmarty(); 

        unset($_SESSION['excel']);

        $smarty->assign('select_projeto', $this->projeto->listarSelectAssoc(array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        )));
        $smarty->displayHBF('selecionar.tpl');
    }

    public function verificar()
    {
        $smarty = $this->getSmarty(); 

        if (isset($_POST['id_projeto']) && $_POST['id_projeto'] != -1) {
            $this->projeto->setId($_POST['id_projeto']);
            $this->projeto->pegar();

            $smarty->assign('nome_projeto', $this->projeto->getData('nome'));

            if ($_FILES['arquivo']['type'] == 'application/vnd.ms-excel') {
                if ($_FILES['arquivo']['size'] < 200000) {
                    if ($_FILES['arquivo']['error'] >= 0) {
                        $excel = new ImportadorExcel();
                        $excel->setFile($_FILES['arquivo']['tmp_name']);

                        $arrayExcel = $excel->getArrayData();

                        // Enviando para sessão para salvar
                        $_SESSION['importar_excel']['excel'] = $arrayExcel['excel'];
                        $_SESSION['importar_excel']['id_projeto'] = $this->projeto->getId();

                        $smarty->assign('cabecalho', $arrayExcel['cabecalho']);
                        $smarty->assign('dados', $arrayExcel['dados']);
                    } else {
                        Mensagem::addErro($_FILES['arquivo']['error']);
                        $smarty->assign('erro', 'erro');
                    }
                } else {
                    Mensagem::addErro(latinToUTF('O arquivo enviado é maior que 2 Mb'));
                    $smarty->assign('erro', 'erro');
                }
            } else {
                Mensagem::addErro(latinToUTF('O arquivo enviado não é .xls do Excel'));
                $smarty->assign('erro', 'erro');
            } 
        } else {
            Mensagem::addErro(latinToUTF('Não foi informado o projeto'));
            $smarty->assign('erro', 'erro');
        }
        $smarty->displayHBF('verificar.tpl');
    }

    public function importar()
    {
        $smarty = $this->getSmarty();

        if (isset($_SESSION['importar_excel']) && $_SESSION['importar_excel'] != '') {
            $excel = new ImportadorExcel();
            $excel->insertData($_SESSION['importar_excel']);
        } else { 
            Mensagem::addErro(latinToUTF('Perdeu a sessão, tente novamente.'));
            $smarty->assign('erro', 'erro');
        }

        $smarty->displayHBF('importar.tpl');
    }
}
?>

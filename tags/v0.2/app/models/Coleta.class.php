<?php
class Coleta extends BaseModel {
    public function __construct( $dbh ) {
        parent::__construct( $dbh );

        $this->table    = 'coleta';
        $this->nameId   = 'id_coleta';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
          'id_coleta'         => '=',
          'id_lagoa'          => '=',
          'id_ponto_amostral' => '=',
          'id_categoria'      => '=',
          'data'              => 'LIKE'
        );
    }
    
    public function dataFormat($tipo, $dataBruta){
        $data = new DateTime($dataBruta);
        switch ($tipo) {
            case 'mensal':
                return date_format($data, 'm/Y H');
                break;

            case 'diario':
                return date_format($data, 'd/m/Y H');
                break;
        }
        return null;
    }

    public function getDataFormated() {
        $dados = $this->getData();
        $dados['data'] = $this->dataFormat($dados['tipo_periodo'], $dados['data']);
        return $dados;
    }
}

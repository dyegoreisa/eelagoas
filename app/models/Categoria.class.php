<?php
class Categoria extends BaseModel {
    public function __construct( $dbh ) {
        parent::__construct( $dbh );

        $this->table    = 'categoria';
        $this->nameId   = 'id_categoria';
        $this->nameDesc = 'nome';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
            'id_categoria' => '=',
            'nome'         => 'LIKE'
        );
    }

    public function temProfundidade() {
        $sth = $this->dbh->prepare("
            SELECT e_perfil 
            FROM categoria c
            WHERE c.id_categoria = :id
            LIMIT 1
        ");

        $sth->execute(array(':id' => $this->getId()));
        $profundidade = $sth->fetch();
        return ($profundidade['e_perfil'] == 1) ? true : false;
    }

    public function listaProfundidades($categorias) {
        $sth = $this->dbh->prepare("
            SELECT c.profundidade 
            FROM categoria ca 
                JOIN coleta c ON c.id_categoria = ca.id_categoria 
            WHERE ca.e_perfil = 1 AND ca.id_categoria IN ({$categorias})           
        ");

        $sth->execute();
        return $this->assocArray($sth->fetchAll(), 'profundidade', 'profundidade');
    }
}

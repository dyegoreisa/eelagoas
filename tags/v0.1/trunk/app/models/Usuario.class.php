<?php
class Usuario extends BaseModel {
  public function __construct( $dbh ) {
    parent::__construct( $dbh );

    $this->table    = 'usuario';
    $this->nameId   = 'id_usuario';
    $this->data     = array();
    $this->dataAll  = array();
    $this->search   = array(
      'id_usuario'  => '=',
      'login'       => 'LIKE',
      'nome'        => 'LIKE',
      'email'       => 'LIKE',
    );
  }

  public function verificar( $login, $senha ) {
    $sth = $this->dbh->prepare('
      SELECT
        u.id_usuario
        , u.nome
      FROM
        usuario u
      WHERE
        login = :login 
        AND senha = md5(:senha)
    ');

    $sth->execute( 
      array(
        ':login' => $login,
        ':senha' => $senha
      )
    );

    $rs = $sth->fetch();

    if( $rs ) {
      $this->id = $rs['id_usuario'];
      return $rs;
    } else {
      return false;
    }
  }

  public function registrarLogin() {
    $sql = "UPDATE usuario SET lastlogin = now() WHERE id_usuario = {$this->id}";
    $this->dbh->exec( $sql );
  }
}

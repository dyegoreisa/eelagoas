<?php
abstract class dao_base
{
    protected $dbh;

    protected $model;

    public function __construct()
    {
        $conn = new Connection();
        $conn->prepare(DB_DRIVER, DB_HOST, DB_NAME, DB_USER, DB_PASSWD); 
        $conn->connect();

        $this->dbh = $conn->dbh;
    }

    public function setModel(model_base $model)
    {
        $this->model = $model;
    }

    protected function inserir($sql, array $parametros)
    {
        $sth = $this->dbh->prepare($sql);
        $ok = $this->executar($sth, $parametros);

        if ($ok) {
            $id = $this->dbh->lastInsertId();
            $this->model->setId($id);
            return $id;
        }

        return FALSE;
    }

    protected function recuperar($sql, array $parametros)
    {
        $sth = $this->dbh->prepare($sql);
        $this->executar($sth, $parametros);

        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    protected function executar(PDOStatement $sth, array $parametros = array())
    {
        $ok = $sth->execute($parametros);

        if ($sth->errorCode() != 00000) {
            $e = $sth->errorInfo();
            throw new Exception("{$e[2]}");
        }

        return $ok;
    }
}

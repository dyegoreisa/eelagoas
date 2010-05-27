<?php

final class import_models_conexao
{
    private $dbh;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        try {
            $this->dbh = new PDO(DATA_DRIVER . ':host=' . DATA_HOST . ';dbname=' . DATA_NAME, 
                                 DATA_USER, 
                                 DATA_PASSWD,
                                 array(PDO::ATTR_PERSISTENT => true));
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    public function getDbh()
    {
        return $this->dbh;
    }
}

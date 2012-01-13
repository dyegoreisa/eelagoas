<?php
class Connection{
  /**
   * dbh 
   * 
   * @var mixed
   * @access public
   */
  public  $dbh;

  /**
   * dsn 
   * 
   * @var mixed
   * @access private
   */
  private $dsn;

  /**
   * user 
   * 
   * @var mixed
   * @access private
   */
  private $user;

  /**
   * passwd 
   * 
   * @var mixed
   * @access private
   */
  private $passwd;

  /**
   * prepare 
   * 
   * @param mixed $driver 
   * @param mixed $host 
   * @param mixed $dbname 
   * @param mixed $user 
   * @param mixed $passwd 
   * @access public
   * @return void
   */
  public function prepare( $driver, $host, $dbname, $user, $passwd ) {
    $this->dsn    = "{$driver}:host={$host};dbname={$dbname}";
    $this->user   = $user;
    $this->passwd = $passwd;
  }

  /**
   * connect 
   * 
   * @access public
   * @return void
   */
  public function connect() {
    try {
      $this->dbh = new PDO($this->dsn, $this->user, $this->passwd, array(PDO::ATTR_PERSISTENT => true));
    } catch (PDOException $e) {
      print "Error!: " . $e->getMessage() . "<br/>";
      die();
    }
  }

}

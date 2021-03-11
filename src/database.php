<?php
class Database{
  protected $servername = "mariadb";
  protected $username = "root";
  protected $password = "example";
  protected $dbname = "webdemo_db";
  
  public function __construct(){
    $this->conn = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);
  }

  public function query($sql){
    $result = $this->conn->query($sql);
    return $result;
  }

  function get($sql)
  {
    $result=mysqli_query($this->conn,$sql);
    $a=array();
    while($row=mysqli_fetch_assoc($result))
    {
      $a[]=$row;
    }
    return $a;
  }


}
?>
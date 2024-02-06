<?php
date_default_timezone_set("asia/taipei");
session_start();

class DB
{
  protected $dsn = "mysql:host=localhost;charset=utf8;dbname=";
  protected $table;
  protected $pdo;

  public function __construct($table)
  {
    $this->table = $table;
    $this->pdo = new PDO($this->dsn, 'root', '');
  }
  function a2s($array)
  {
    foreach ($array as $col => $val) {
      $tmp[] = "`$col`='$val'";
    }
    return $tmp;
  }

  function sql_all($sql, $array, $other)
  {
    if (isset($this->table) && !empty($this->table)) {
      if (is_array($array)) {
        $tmp = $this->a2s($array);
        $sql .= "where" . join("&&", $tmp);
      } elseif (is_numeric($array)) {
        $sql .= "$array";
      }
      $sql.=$other;
      return $sql;
    }
  }



  function q($sql)
  {
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }
  function all($array='',$other='')
  {
$sql="select * from `$this->table`";
$sql=$this->sql_all($sql, $array, $other);
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

  }
  function
  count($array = '', $other = '')
  {
    $sql = "select count(*) from `$this->table`";
    $sql = $this->sql_all($sql, $array, $other);
    return $this->pdo->query($sql)->fetchColumn();

  }
  function find($id)
  {
    $sql = "select count(*) from `$this->table`";
    if(is_array($id){
$tmp=$this->a2s($id);
$sql="where".("&&",$tmp);
    }elseif(is_numeric($id)){

$sql.="where `id` ='$id'";
    })
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

  }
  function save()
  {
  }
  function del()
  {
  }



  function math($math,$col,$array='',$other='')
  {
    $sql="select $math($col) from `$this->table`";
    $sql=$this->sql_all($sql,$array,$other);
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }
  function sum($col='',$array='',$other='')
  {
    return $this->math('sum',$col,$array,$other);
  }
  function
  max($col = '', $array = '', $other = '')
  {
      return $this->math('max', $col, $array, $other);
  }
  function
    min($col = '', $array = '', $other = '')
  { {
        return $this->math('min', $col, $array, $other);
  }
}

function dd()
{
}
function to()
{
}

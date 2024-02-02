<?php
date_default_timezone_set("Asia/Taipei"); //設定網站的時區為亞洲台北
session_start();
// 很多功能需要透過 session 來暫存狀態
// 因此我們可以在共用檔中先啟用 session
// 方便在各個頁面都可以操作 session
class DB
{
    //  $dsn 用來作為 PDO 的資料庫設定 dbname 為使用的資料庫名稱
    //  $table 使用的資料表名
    //  $pdo PDO 的物件變數
    protected $dsn = "mysql:host=localhost;charset=utf8;dbname=db19_2";
    protected $pdo;
    protected $table;

    // 建立建構式，在建構時帶入 table 名稱會建立資料庫的連線
    // 建構式為物件被實例化(new DB)時會先執行的方法
    public function __construct($table)
    {
        //將物件內部的 $table 值設為帶入的 $table
        $this->table = $table;
        //將物件內部的 $pdo 值設為 PDO 建立的資料庫連線物件        
        $this->pdo = new PDO($this->dsn, 'root', '');
    }

    // $table->all()-查詢符合條件的全部資料
    function all($where = '', $other = '')
    {
        $sql = "select * from `$this->table` ";
        $sql = $this->sql_all($sql, $where, $other);
        return  $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    function count($where = '', $other = '')
    {
        $sql = "select count(*) from `$this->table` ";
        $sql = $this->sql_all($sql, $where, $other);
        return  $this->pdo->query($sql)->fetchColumn();
    }
    private function math($math, $col, $array = '', $other = '')
    {
        $sql = "select $math(`$col`)  from `$this->table` ";
        $sql = $this->sql_all($sql, $array, $other);
        return $this->pdo->query($sql)->fetchColumn();
    }
    function sum($col = '', $where = '', $other = '')
    {
        return  $this->math('sum', $col, $where, $other);
    }
    function max($col, $where = '', $other = '')
    {
        return  $this->math('max', $col, $where, $other);
    }
    function min($col, $where = '', $other = '')
    {
        return  $this->math('min', $col, $where, $other);
    }

    // $table->find($id)-查詢符合條件的單筆資料
    function find($id)
    {
        $sql = "select * from `$this->table` ";

        if (is_array($id)) {
            $tmp = $this->a2s($id);
            $sql .= " where " . join(" && ", $tmp);
        } else if (is_numeric($id)) {
            $sql .= " where `id`='$id'";
        }
        //echo 'find=>'.$sql;
        $row = $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    function save($array)
    {
        if (isset($array['id'])) {
            $sql = "update `$this->table` set ";

            if (!empty($array)) {
                $tmp = $this->a2s($array);
            }

            $sql .= join(",", $tmp);
            $sql .= " where `id`='{$array['id']}'";
        } else {
            $sql = "insert into `$this->table` ";
            $cols = "(`" . join("`,`", array_keys($array)) . "`)";
            $vals = "('" . join("','", $array) . "')";

            $sql = $sql . $cols . " values " . $vals;
        }

        return $this->pdo->exec($sql);
    }

    function del($id)
    {
        $sql = "delete from `$this->table` where ";

        if (is_array($id)) {
            $tmp = $this->a2s($id);
            $sql .= join(" && ", $tmp);
        } else if (is_numeric($id)) {
            $sql .= " `id`='$id'";
        }
        //將sql句子帶進pdo的exec方法中，回傳的結果是影響了幾筆資料
        return $this->pdo->exec($sql);
    }

    //  用來處理較複雜的sql語句，比如子查詢或聯表查詢
    //  預設回傳的資料是2維陣列
    //  可輸入各式SQL語法字串並直接執行

    function q($sql)
    {
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    //  此方法僅供類別內部使用，外部無法呼叫
    //  帶入的參數必須為 key-value 型態的陣列
    //  陣列透過 foreach 轉化為 `key`='value'的字串存入陣列中
    //  回傳此字串陣列供其他方法使用

    private function a2s($array)
    {
        foreach ($array as $col => $value) {
            $tmp[] = "`$col`='$value'";
        }
        return $tmp;
    }


    //   此方法僅供類別內部使用，外部無法呼叫
    //   $sql 一個sql的字串，主要是where 前的語法
    //   ...$arg 使用不定參數，表示參數可能不只一個
    //   根據不同的參數內容，會組合出適合的sql語句來回傳

    private function sql_all($sql, $array, $other)
    {
        if (isset($this->table) && !empty($this->table)) {

            if (is_array($array)) {

                if (!empty($array)) {
                    $tmp = $this->a2s($array);
                    $sql .= " where " . join(" && ", $tmp);
                }
            } else {
                $sql .= " $array";
            }

            $sql .= $other;
            // echo 'all=>'.$sql;
            // $rows = $this->pdo->query($sql)->fetchColumn();
            return $sql;
        }
    }
}
//  用來顯示陣列內容-除錯時使用 
function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
function to($url)
{
    header("location:$url");
}

$Total = new DB('total');
$User = new DB('user');
$News = new DB('news');
$Que = new DB('ques');
$Log = new DB('log');

if (!isset($_SESSION['visited'])) {
    if ($Total->count(['date' => date('Y-m-d')]) > 0) {
        $total = $Total->find(['date' => date('Y-m-d')]);
        $total['total']++;
        $Total->save($total);
    } else {
        $Total->save(['total' => 1, 'date' => date('Y-m-d')]);
    }

    $_SESSION['visited'] = 1;
}

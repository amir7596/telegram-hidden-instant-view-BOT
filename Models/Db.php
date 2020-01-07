<?php
namespace Models;

class Db
{
    protected static $pdo;
    protected $tbl;
    protected const DBNAME="DBNAME";
    protected const DBUSERNAME="DBUSERNAME";
    protected const DBPASSWORD="DBPASS";
    protected const DBPORT="3306";

    public function __construct()
    {
        try
        {
            $this->config();
        }
        catch (Exception $e)
        {
            echo "متصل نشد . خطا در اتصال به دیتابیس";
        }
    }

    public function config()
    {
        if(is_object(self::$pdo))
        {
            return;
        }
        else
        {
           self::$pdo = new \PDO("mysql:host=localhost;dbname=".self::DBNAME.";port=".self::DBPORT, self::DBUSERNAME, self::DBPASSWORD);
           self::$pdo->exec('SET NAMES utf8');
           self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
           self::$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
           self::$pdo->exec( "SET NAMES 'utf8mb4'" );
           //self::$pdo->exec( "SET CHARACTER SET utf8" );
           //self::$pdo->exec( "SET SESSION collation_connection = 'utf8mb4_unicode_ci'" );
        }
    }

    public function settbl($name)
    {
        $this->tbl = $name;
    }

    public function search_data($name, $val)
    {
        $cond="";
        if(is_array($name) and is_array($val))
        {
            foreach ($name as $key=>$value)
            {
                $cond.=$value." = '".$val[$key]."'";
                if ($key<sizeof($name)-1)
                {
                    $cond.=" and ";
                }
            }
        }
        else
        {
            $cond=$name." = '".$val."'";
        }
        $sql =self::$pdo->prepare("SELECT * FROM {$this->tbl} WHERE $cond");
        $sql->execute();
        $row = $sql->fetch(\PDO::FETCH_OBJ);
        return ($row);
    }
    public function add_data($data)
    {

        $fild = array_keys($data);

        if (is_array($data)) {
            $dat = "'" . implode("','", $data) . "'";
        }
        if (is_array($fild)) {
            $fil = implode(" , ", $fild);
        }

        $sql =self::$pdo->prepare("INSERT INTO `{$this->tbl}`  ($fil) VALUES ($dat)");

        $sql->execute();
        return true;
    }
    public function select_data($name,$val,$additional="")
    {
        $cond="";
        if(is_array($name) and is_array($val))
        {
            foreach ($name as $key=>$value)
            {
                $cond.=$value." = '".$val[$key]."'";
                if ($key<sizeof($name)-1)
                {
                    $cond.=" and ";
                }
            }
        }
        else if(!empty($name))
        {
            $cond=$name." = '".$val."'";
        }
        else
        {
            $cond="1";
        }
        $sql =self::$pdo->prepare("SELECT * FROM {$this->tbl} WHERE $cond $additional" );
        $sql->execute();
        $row = $sql->fetchAll(\PDO::FETCH_OBJ);

        return ($row);
    }
    public function update_data($data,$cdata)
    {
        $upd="";
        if(is_array($data))
        {
            $i=0;
            foreach ($data as $key=>$value)
            {
                $upd.=$key." = '".$value."'";
                if ($i<sizeof($data)-1)
                {
                    $upd.=" , ";
                }
                $i++;
            }
        }
        else
        {
            $upd=$data;
        }
        $cond="";
        if(is_array($cdata))
        {
            $j=0;
            foreach ($cdata as $key=>$value)
            {
                $cond.=$key." = '".$value."'";
                if ($j<sizeof($cdata)-1)
                {
                    $cond.=" , ";
                }
                $j++;
            }
        }
        else
        {
            $cond=$cdata;
        }
        file_put_contents("log.txt","update {$this->tbl} set $upd WHERE $cond");
        $sql =self::$pdo->prepare("update {$this->tbl} set $upd WHERE $cond");
        $sql->execute();
    }

    public function delete_data($cdata)
    {
        $cond="";
        if(is_array($cdata))
        {
            $j=0;
            foreach ($cdata as $key=>$value)
            {
                $cond.=$key." = '".$value."'";
                if ($j<sizeof($cdata)-1)
                {
                    $cond.=" , ";
                }
                $j++;
            }
        }
        else
        {
            $cond=$cdata;
        }
        $sql =self::$pdo->prepare("delete from {$this->tbl} WHERE $cond ");

        $sql->execute();

    }

    public function like_data($name, $value)
    {
        $sql =self::$pdo->prepare("select * FROM {$this->tbl} where $name LIKE '%$value%'");
        $sql->execute();
        $results = $sql->fetchAll(\PDO::FETCH_OBJ);
        return $results;
    }
    public function directQuery($query)
    {
        $sql=self::$pdo->prepare($query);
        $sql->execute();
        $row = $sql->fetchAll(\PDO::FETCH_OBJ);
        return ($row);
    }
}

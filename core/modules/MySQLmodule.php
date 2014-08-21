<?php


/**
 ************************************************************************
 * BulkAPI
 *
 * An open source application development framework for PHP 5.3.0 or newer
 *
 * @package		BulkAPI - water fusion
 * @author		Shariar Shaikot
 * @copyright	Copyright (c) 2014, AnonnaFrontEnd
 * @license		http://www.apache.org/licenses/LICENSE-2.0
 * @link		http://bulkapi.anonnafrontend.com
 * @since		Version (water fusion)
 *************************************************************************
 */

class MySQLhelper {

    public $mysqli;

    private $table;

    /**
     *
     */

    public function __construct()
    {
        error_reporting(0);
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        if($this->mysqli->connect_error)
        {
            throw new Exception('MySQLi connection failed with error number: '.$this->mysqli->connect_errno.' Please check your configuration file.');
        }else{
            return true;
        }
    }

    private function getColumns()
    {
        $query = $this->mysqli->query("SHOW COLUMNS FROM ".$this->table);
        $arr = array();
        while($row = $query->fetch_object())
        {
            $arr[] = $row->Field;
        }

        $query->free_result();

        return $arr;
    }

    /**
     * @param $table
     * @param $entry
     * @return bool|mysqli_result
     * @throws Exception
     */

    public function Create($table, $entry)
    {
        $this->table = $table;

        if(count($entry)<1)
        {

            throw new Exception('Expecting "columns" parameter as Array with at least 1 value, thrown - on file: '.__FILE__.' and line number: '.__LINE__);

        }else{

               $col = $this->getColumns();

            foreach($entry as $key=>$val)
            {
                if(array_search($key, $col))
                {
                    $keys[] = $key;
                    $vals[] = "'".$val."'";
                }else{
                    throw new Exception('One or more field has not matched with table fields when inserting items!');
                }
            }
            if(count($keys)>1)
            {
                $keys = implode(', ', $keys);
                $vals = implode(', ', $vals);
            }else{
                $keys = $keys[0];
                $vals = $vals[0];
            }

            $query = "INSERT INTO ".$this->table." (".$keys.") VALUES (".$vals.")";

            return $this->mysqli->query($query);

        }
    }

    /**
     * @param $table
     * @param $columns
     * @param array $where
     */

    public function Read($table, $columns, $where=array(1=>1))
    {
        $this->table = $table;

        $where_string = "WHERE ";
        if(is_array($where)&&count($where)==1)
        {
            $where_string .= key($where). "='" .$where[key($where)]. "'";
        }elseif(is_string($where)&&strstr($where, '='))
        {
            $where_string .= $where;
        }else{
            throw new Exception("MySQLi string 'WHERE' columns has an invalid format");
        }
    }

    /**
     * @param $table
     * @param $columns
     * @param array $where
     */

    public function Update($table, $columns, $where=array(1=>1))
    {
        $this->table = $table;

        $where_string = "WHERE ";
        if(is_array($where)&&count($where)==1)
        {
            $where_string .= key($where). "='" .$where[key($where)]. "'";
        }elseif(is_string($where)&&strstr($where, '='))
        {
            $where_string .= $where;
        }else{
            throw new Exception("MySQLi string 'WHERE' columns has an invalid format");
        }

        $update_columns = '';

        if(is_array($columns)&&count($columns))
        {
            foreach($columns as $field=>$value)
            {
                $update_columns .= $field;
                $update_columns .= "='";
                $update_columns .= $value."'";
                if(end($columns)!=$value)
                {
                    $update_columns .= ', ';
                }
            }
        }elseif(is_string($columns)&&strstr($columns, '='))
        {
            $update_columns = $columns;
        }else{
            throw new Exception("MySQLi update columns has an invalid format");
        }

        $query = "UPDATE ";
        $query .= $this->table;
        $query .= " SET ";
        $query .= $update_columns;
        $query .= $where_string;

        return $this->mysqli->query($query);
    }

    /**
     * @param $table
     * @param array $where
     * @return bool|mysqli_result
     * @throws Exception
     */

    public function Delete($table, $where=array(1=>1))
    {
        $this->table = $table;

        $where_string = "WHERE ";
        if(is_array($where)&&count($where)==1)
        {
            $where_string .= key($where). "='" .$where[key($where)]. "'";
        }elseif(is_string($where)&&strstr($where, '='))
        {
            $where_string .= $where;
        }else{
            throw new Exception("MySQLi string 'WHERE' columns has an invalid format");
        }

        $query = "DELETE FROM ";
        $query .= $this->table;
        $query .= " ";
        $query .= $where_string;

        return $this->mysqli->query($query);
    }

    public function __destruct()
    {
        error_reporting(E_ALL);
        //$this->mysqli->close();
    }
}
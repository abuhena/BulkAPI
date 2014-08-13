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

            foreach($entry as $key=>$val)
            {
                if(array_search($key, $this->getColumns()))
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
    }

    /**
     * @param $table
     * @param $columns
     * @param array $where
     */

    public function Update($table, $columns, $where=array(1=>1))
    {
        $this->table = $table;
    }

    /**
     * @param $table
     * @param array $where
     */

    public function Delete($table, $where=array(1=>1))
    {
        $this->table = $table;
    }

    public function __destruct()
    {
        error_reporting(E_ALL);
        //$this->mysqli->close();
    }
}
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

class SessionSaveHandler extends MySQLhelper implements SessionHandlerInterface
{
    private $table;

    function open($savePath, $sessionName)
    {
        $this->table = SESSION_STORAGE_TABLE_NAME;

        $query = "CREATE TABLE IF NOT EXISTS ".$this->table." (id INTEGER(11) NOT NULL AUTO_INCREMENT, ";
        $query .= "session_id VARCHAR(255) NOT NULL, session_data VARCHAR(500) NOT NULL, ";
        $query .= "session_timestamp INTEGER(20) NOT NULL, PRIMARY KEY (id))";
        if($this->mysqli->query($query))
        {
            return true;
        }else{
            throw new Exception("Unable to create session handler table in ".DB_DATABASE." database. Reason: ". $this->mysqli->error);
        }
    }

    function close()
    {
        return true;
    }

    function read($id)
    {
        $query = "SELECT session_data FROM ".$this->table." WHERE session_id='".$id."'";
        $exec = $this->mysqli->query($query);
        if($exec->num_rows>0)
        {
            return $exec->fetch_object()->session_data;
        }
    }

    function write($id, $data)
    {
        $select_query = "SELECT * FROM ".$this->table." WHERE session_id='".$id."'";
        $exec = $this->mysqli->query($select_query);
        if($exec->num_rows<1)
        {
            $query = "INSERT INTO ".$this->table;
            $query .= " (session_id, session_data, session_timestamp) VALUES";
            $query .= " ('".$id."', '".$data."', '".time()."')";
            if($this->mysqli->query($query))
            {
                return true;
            }else{
                return false;
            }
        }else{
            $update_query = "UPDATE ".$this->table." SET session_timestamp='".time()."', session_data='".$data."' WHERE session_id='".$id."'";
            if($this->mysqli->query($update_query))
            {
                return true;
            }
        }
    }

    function destroy($id)
    {
        $query = "DELETE FROM ".$this->table." WHERE id='".$id."'";
        if($this->mysqli->query($query))
        {
            return true;
        }else{
            return false;
        }
    }

    function gc($maxlifetime)
    {
        $query = "DELETE FROM ".$this->table." WHERE session_timestamp+".$maxlifetime."<".time();
        $this->mysqli->query($query);
        return true;
    }
}
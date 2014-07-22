<?php
/**
 * Created by PhpStorm.
 * User: shaikot
 * Date: 7/20/14
 * Time: 3:19 AM
 */

class Analytics {

    private $db;
    private $ua;
    private $ua_md5;
    private $ip;
    private $ip_md5;
    private $metadata;

    /**
     *
     */

    public function __construct()
    {
        $this->db = new SQLite3('system/database/Analytics.db');

        $this->ua = isset($_SERVER['HTTP_USER_AGENT']) ? htmlspecialchars($_SERVER['HTTP_USER_AGENT']) : null;
        $this->ua_md5 = md5($this->ua);

        $this->ip = isset($_SERVER['REMOTE_ADDR']) ? htmlspecialchars($_SERVER['REMOTE_ADDR']) : null;
        $this->ip_md5 = md5($this->ip);

        $client_tbl_query = "CREATE TABLE IF NOT EXISTS Clients (id INTEGER NOT NULL PRIMARY KEY, ";
        $client_tbl_query .= "IP_Address TEXT NOT NULL, USER_AGENT TEXT NOT NULL, ";
        $client_tbl_query .= "USER_AGENT_MD5 TEXT NOT NULL, IP_Address_MD5 TEXT NOT NULL)";
        $this->db->exec($client_tbl_query);

        $data_tbl_query = "CREATE TABLE IF NOT EXISTS Data (id INTEGER NOT NULL PRIMARY KEY, ";
        $data_tbl_query .= "Client_Id INTEGER NOT NULL, RequestUri TEXT NULL, RequestFilename TEXT NULL, ";
        $data_tbl_query .= "RequestFor TEXT NOT NULL, RequestTime INTEGER NOT NULL)";
        $this->db->exec($data_tbl_query);
    }

    /**
     * @param array $metadata
     * @return bool
     */

    public function eventEntry(array $metadata)
    {
        $this->metadata = (object) $metadata;

        $data_tbl_insert_query = "INSERT INTO Data (Client_Id, RequestUri, RequestFilename, RequestFor, RequestTime) VALUES ";
        $data_tbl_insert_query .= "('".$this->getClientId()."', '".htmlspecialchars($this->metadata->RequestUri)."', ";
        $data_tbl_insert_query .= "'".htmlspecialchars($this->metadata->RequestFilename)."', '".htmlspecialchars($this->metadata->RequestFor)."', ";
        $data_tbl_insert_query .= "'".time()."')";

        return $this->db->exec($data_tbl_insert_query);
    }

    /**
     * @return int
     */

    private function getClientId()
    {
        $data_tbl_get_query = "SELECT id FROM Clients ";
        $data_tbl_get_query .= "WHERE USER_AGENT_MD5='".$this->ua_md5."'";
        $data_tbl_get_query .= " OR ";
        $data_tbl_get_query .= "IP_Address_MD5='".$this->ip_md5."'";

        $query = $this->db->query($data_tbl_get_query);
        $fetch = $query->fetchArray();
        if($fetch)
        {
            return $fetch['id'];
        }else{
            return $this->userEntry();
        }
    }

    /**
     * @return int
     * @throws Exception
     */

    private function userEntry()
    {
        $client_tbl_insert_query = "INSERT INTO Clients ";
        $client_tbl_insert_query .= "(IP_Address, USER_AGENT, USER_AGENT_MD5, IP_Address_MD5) VALUES ";
        $client_tbl_insert_query .= "('".$this->ip."', '".$this->ua."', '".$this->ua_md5."', '".$this->ip_md5."')";

        if($this->db->exec($client_tbl_insert_query))
        {
            return $this->db->lastInsertRowID();
        }else{
            throw new Exception('There is a critical SQLite syntax error');
        }
    }
}
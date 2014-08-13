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

class SessionMangement {

    public $output;
    private $output_buffer;
    private $session;
    private $savePath;

    public function __construct()
    {
        $this->output_buffer = ob_get_contents();
        if(!$this->output_buffer)
        {
            session_start();
        }else{
            ob_clean();
            $this->output = $this->output_buffer;
            session_start();
        }
    }

    public function __get($name)
    {
        if(isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }else{
            throw new Exception('Undefined session offset "'.$name.'"!');
        }
    }

    public function __set($name, $value)
    {
        $this->session = new stdClass();
        $this->session->$name = is_array($value) ? (object)$value : $value;
        $_SESSION[$name] = $this->session->$name;
    }

    public function __unset($name)
    {
        if(isset($_SESSION[$name]))
        {
            unset($_SESSION[$name]);
        }else{
            throw new Exception('Undefined session offset "'.$name.'"!');
        }
    }


}
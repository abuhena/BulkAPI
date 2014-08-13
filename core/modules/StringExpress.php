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

class StringExpress {


    private $input;
    private $output;

    public function __construct($string)
    {
        $this->input = isset($string) ? $string : null;
        if(!is_string($this->input))
        {
            throw new Exception('Argument only accept string');
        }
    }

    public function toUpper()
    {
        return strtoupper($this->input);
    }

    public function toLower()
    {
        return strtolower($this->input);
    }

    public function toHSChars()
    {
        return htmlspecialchars($this->input);
    }

    public function toTrim()
    {
        return trim($this->input);
    }

    public function toEscape()
    {
        if(!function_exists('mysqli_real_escape_string'))
        {
            return mysql_escape_string($this->input);
        }else{
            return mysql_real_escape_string($this->input);
        }
    }

    public function toHash($crypt='md5')
    {
        switch($crypt)
        {
            case 'md5':
                return md5($this->input);
            break;

            case 'sha1':
                return sha1($this->input);
            break;
        }
    }

    public function toReplace($src, $replace)
    {
        return str_replace($src, $replace, $this->input);
    }

    public function toFilter()
    {
        $this->output = $this->toHSChars();
        $this->output = $this->toEscape();
        $this->output = $this->toTrim();
        return $this->output;
    }

    public function toDetails()
    {
        $ret = array();
        $ret['length'] = strlen($this->input);
        $ret['word'] = count(explode(' ', $this->input));

        return $ret;
    }

    public function __destruct()
    {
        unset($this->input);
        unset($this->output);
    }

}
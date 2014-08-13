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

class Application extends BulkAPI {


    /**
     * @note: your application logic methods should goes below here
     */


   public final function debug()
   {

       try {
           $session = $this->load('session');

           $session->json = array('value' => 'X Factor');
           $session->curl;

       } catch (Exception $e)
       {
           $err = $this->load('string', $e->getMessage());

           $this->json(array(
               'success' => false,
               'response' => $err->toHSChars(),
               //'message' => $vars->message,
               'session' => $_SESSION['json'],
               'status' => 500
           ), $this->callback);
       }

   }


}
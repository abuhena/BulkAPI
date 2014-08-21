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
       $session = $this->load('session');

       $session->curl;
   }

    public final function upload()
    {
        $uploader = $this->load('uploader');
        $uploader->imageUpload($_FILES['file'], 'hello/world', '1000x1000');
        echo $uploader->getLastFileUri();
        echo PHP_EOL;
        echo $uploader->getLastFileUrl();
    }


}
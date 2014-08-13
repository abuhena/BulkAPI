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

class BulkAPI extends restClient {

    /**
     * This class containing system configurations
     * though the configuration settings and files hosted
     * in system/ directory.
     *
     * All final access modifier method will be called by HTTP request
     * same as Application class, but this class is dynamically setup
     * for accessing final methods.
     */

    public final function system_login()
    {
        $this->json(array(
           'success' => true,
            'response' => 'Testing method',
            'status' => 200
        ));
    }

    /**
     *
     */

    public final function system_index()
    {
        print_r($this->load('session')->json->value);
    }

    /**
     *
     */

    public final function system_()
    {

    }



    /**
     * @usage this method used by the framework system itself
     * @message you can't remove this method, you just able to modify this method per html module basis
     */

    public final function index()
    {
        $this->load('html');
    }


    /**
     * @use         Apply when a HTML request doesn't exists
     * @return      String
     */

    public function docsError()
    {
        $this->load('html');
        $this->html->htmlBind(array(
            'html' => array(
                "head" => array(
                    "master_css" => true,
                    "title" => "Not Found"
                ),
                "body" => array(
                    "Nav" => array(
                        "class" => "navbar navbar-inverse navbar-static-top",
                        "id" => "main-navbar",
                        "elements" => array(
                            "Div" => array(
                                "class" => "container",
                                "elements" => array(
                                    "Formatting" => array(
                                        "tag" => "a",
                                        "href" => "#home",
                                        "class" => "navbar-brand",
                                        "text" => "Home"
                                    )
                                )
                            )
                        )
                    ),
                    "Div" => array(
                        "class" => "container",
                        "elements" => array(
                            "Div" => array(
                                "class" => "jumbotron",
                                "style" => "background-color: #f2dede !important;",
                                "elements" => array(
                                    "Formatting" => array(
                                        "tag" => "h2",
                                        "class" => "danger",
                                        "text" => "404 Not Found <small>we can not find any assosiative page.</small>"
                                    )
                                )
                            )
                        )
                    ),

                    "externalJS[1]" => htmlspecialchars('http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js'),
                    "externalJS" => htmlspecialchars('http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js')
                )
            )
        ));
    }
}
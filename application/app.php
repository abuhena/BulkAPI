<?php
/**
 * Created by PhpStorm.
 * User: shaikot
 * Date: 7/7/14
 * Time: 11:53 PM
 */

class Application extends restClient {

    /**
     * @note: your application logic methods should goes below here
     */


   public final function debug()
   {
       try {
           $gcm = $this->load('gcm');
       } catch (Exception $e)
       {
           $this->json(array(
               'success' => false,
               'response' => $e->getMessage(),
               'status' => 500
           ), $this->callback);
       }

   }


    /* ------------ */


    /**
     * @usage this method used by the framework system itself
     * @message you can't remove this method, you just able to modify this method per html module basis
     */

    public final function index()
    {
        $this->load('html');
    }

    /**
     *
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
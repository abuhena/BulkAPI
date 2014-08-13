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


class restClient {

    public $callback;
    public $parameters;

    private $call;
    private $analytics;


    /**
     * @var
     * @message this all properties will be used in parseUri() method
     */
    private $fullUri;
    private $requestUri;
    private $realPath;
    private $filePath;
    private $hostUri;
    private $fullFile;
    public $requestFilename;
    public $requestFilextention;
    protected static $protocol;


    protected $application;
    protected $system;

    /**
     * @var string
     * @message will be constructed at initiation of class for error/warning/fault messages
     */

    public static $appFailed;
    public static $apiNotFound;
    public static $paramFailed;
    public static $directoryFailed;
    public static $filesystemFailed;
    public static $fileNotFound;


    /**
     * Helper properties
     */

    protected $mysql;
    protected $html;
    protected $string;



    function __construct()
    {
        self::$protocol = $_SERVER['REQUEST_SCHEME'];
        self::$appFailed = "The API reciever has not configured yet!";
        self::$apiNotFound = "Requested API does not found or have not been configured yet!";
        self::$paramFailed = "Parameter does not appears as correct JSON syntax!";
        self::$directoryFailed = "No such directory";
        self::$filesystemFailed = "The file system is locked for this directory";
        self::$fileNotFound = "No such file in this directory";

        $this->callback = isset($_GET['callback']) ? htmlspecialchars($_GET['callback']) : false;

        $this->application = file_exists('application/Application.php') ? 'Application' : false;
        $this->system = 'BulkAPI';
    }

    /**
     * @param $func
     * @param $args
     */

    public function __call($func, $args)
    {
        if(!method_exists(__CLASS__, $func))
        {
            $this->json(array(
                'success' => false,
                'response' => self::$apiNotFound,
                'status' => 404
            ));
        }
    }


    /**
     * @param $type
     * @param $ext
     * @param null $additional
     */

    protected function setHeader($type, $ext, $additional=NULL)
    {
        header('Cache-Control: no-cache');
        header('Accept-Charset: utf-8');
        if($additional!=null&&count($additional)>=1)
        {
            foreach($additional as $head)
            {
                header($head);
            }
        }

        switch($type)
        {
            case 'api':
                header('Content-type: application/json');
            break;
            case 'docs':
                header('Content-type: text/html');
            break;
            default :
                header('Content-type: ' .$this->extToMime($ext));
            break;
        }
    }

    /**
     * @param $code
     */

    protected function setHTTPStatus($code)
    {
        switch ($code)
        {
            case 404 :
                header("HTTP/1.1 404 Not Found");
                break;
            case 500 :
                header("HTTP/1.1 500 Internal server error");
                break;
            case 401 :
                header("HTTP/1.1 401 Unauthorized Access");
                break;
            case 502 :
                header("HTTP/1.1 502 Bad gateway");
                break;
            case 400 :
                header("HTTP/1.1 400 Bad request");
                break;
            case 403 :
                header("HTTP/1.1 403 Access Forbidden");
                break;
            case 200 :
                header("HTTP/1.1 200 OK");
                break;
        }
    }

    /**
     * @return array
     */

    public function parseUri()
    {
        $protocol = self::$protocol;
        $protocol .= '://';

        $this->hostUri = $protocol;
        $this->hostUri .= $_SERVER['HTTP_HOST'];

        $this->fullUri = $this->hostUri;
        $this->fullUri .= $_SERVER['REQUEST_URI'];
        $this->requestUri = $_SERVER['REQUEST_URI'];

        $this->realPath = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));

        //$this->filePath = substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/'));

        if(isset($_SERVER['REDIRECT_URL']))
        {
            $this->requestFilextention = substr($_SERVER['REDIRECT_URL'], strpos($_SERVER['REDIRECT_URL'], '.')+1);
            $this->requestFilename = substr($_SERVER['REDIRECT_URL'], strlen($this->realPath.'/'), -(strlen($this->requestFilextention)+1));
            $this->fullFile = $this->requestFilename.$this->requestFilextention;
            $this->filePath = strstr($this->fullFile, '/') ? substr($this->fullFile, 0, strrpos($this->fullFile, '/')+1) : '/';

        }else{
            $this->requestFilename = 'index';
            $this->requestFilextention = 'html';
        }

        return array(
            'hostUri' => $this->hostUri,
            'fullUri' => $this->fullUri,
            'requestUri' => $this->requestUri,
            'realPath' => $this->realPath,
            'filePath' => $this->filePath,
            'requestFilename' => $this->requestFilename,
            'requestFilextention' => $this->requestFilextention
        );
    }

    /**
     * @return bool
     */

    private function methodVerifier()
    {
        $class = 'Application';
        //echo $this->call; die;
        $call = strstr($this->call, 'system/') ? str_replace('/', '_', $this->call) : $this->call;

            $reflection = new ReflectionClass($class);
        $methods = $reflection->getMethods(ReflectionMethod::IS_FINAL);
        $methods = json_decode(json_encode($methods) ,true);
        //print_r($this->requestFilename); die;
        for($i=0; count($methods)>$i; $i++)
        {
            if($methods[$i]['name']==$call)
            {
                $this->call = $call;

                return true;
            }
        }
        return false;
    }

    public function useAnalytics()
    {
        $this->analytics = new Analytics();
        return $this->analytics;
    }

    /**
     * Just about to load external helpers
     */

    public function load($helper, $param=NULL)
    {
        $helper = strtolower($helper);
        $class = null;

        switch ($helper)
        {
            case 'mysql' :
                $class = new MySQLhelper();

            break;

            case 'html' :
                $class = new HtmlHelper();
                $this->html = $class;
            break;

            case 'gcm' :
                $class = new GCMmodule();
            break;

            case 'analytics':
                $class = new Analytics();
            break;

            case 'gd':
                $class = new GDBasic();
            break;

            case 'string':
                $class = new StringExpress($param);
            break;

            case 'session':
                if(CUSTOM_SESSION_SAVE_HANDLER)
                {
                    $handler = new SessionSaveHandler();
                    session_set_save_handler(
                        array($handler, 'open'),
                        array($handler, 'close'),
                        array($handler, 'read'),
                        array($handler, 'write'),
                        array($handler, 'destroy'),
                        array($handler, 'gc')
                    );
                }
                $class = new SessionMangement();

            break;

            case 'parameters':

                $this->loadParameters($_REQUEST);
                $class = $this->parameters->args;
            break;

            default:
                throw new Exception('Module is not registred yet!');
            break;
        }

        return $class;
    }

    /**
     * @param $method
     * @param $call
     * @param null $args
     */

    public function request($method, $call, $args=NULL)
    {
        $this->call = $call;

        if(USE_BASIC_ANALYTICS)
        {
            $this->useAnalytics()->eventEntry(array('RequestUri' => $this->fullUri,
                'RequestFilename' => $this->requestFilename.'.'.$this->requestFilextention, 'RequestFor' => $this->getRequestType()));
        }

        switch ($method)
        {
            //Handle the POST api request
            case 'post':
                $this->setHeader('api', $this->requestFilextention, array('Access-Control-Allow-Origin: *'));
                if($this->application)
                {
                    if($this->methodVerifier())
                    {
                        if($args!=NULL&&!$this->loadParameters($args))
                        {
                            $this->json(array(
                                    "success" => false,
                                    "response" => self::$paramFailed,
                                    "status" => 500
                                ),
                                $this->callback
                            );
                        }else{
                            $this->application = new Application();
                            $call = $this->call;
                            $this->application->$call();
                        }
                    }else{
                        $this->json(array(
                                "success" => false,
                                "response" => self::$apiNotFound,
                                "status" => 404
                            ),
                            $this->callback
                        );
                    }
                }else{
                    $this->json(array(
                        "success" => false,
                         "response" => self::$appFailed,
                           "status" => 500
                        ),
                        $this->callback
                    );
                }
            break;

            //Handle GET api request

            case 'get':

                $this->setHeader('api', $this->requestFilextention, array('Access-Control-Allow-Origin: *'));


                if($this->application)
                {
                    if($this->methodVerifier())
                    {
                        $this->loadParameters($args);
                        $this->application = new Application();
                        $call = $this->call;
                        $this->application->$call();
                    }else{
                        $this->json(array(
                                "success" => false,
                                "response" => self::$apiNotFound,
                                "status" => 404
                            ),
                            $this->callback
                        );
                    }
                }else{
                    $this->json(array(
                            "success" => false,
                            "response" => self::$appFailed,
                            "status" => 500
                        ),
                        $this->callback
                    );
                }
            break;

            //Handle file output request - particularly handle non JSON or HTML extention request

            case 'file':

                if($this->application)
                {
                    if(is_dir($this->filePath))
                    {
                        $dirState = $this->dirState($this->filePath);
                        if(isset($dirState['locked'])&&$dirState['locked']==FALSE)
                        {
                            if(file_exists($this->requestFilename.'.'.$this->requestFilextention))
                            {
                                $this->setHeader('file', $this->requestFilextention, array());

                                $file = $this->requestFilename.'.'.$this->requestFilextention;

                                ob_start();

                                echo $this->streamFile($file);



                            }else{
                                $this->setHeader('api', $this->requestFilextention, array());
                                $this->json(array(
                                        "success" => false,
                                        "response" => self::$fileNotFound,
                                        "status" => 404
                                    ),
                                    $this->callback
                                );
                            }
                        }else{
                            $this->setHeader('api', $this->requestFilextention, array());
                            $this->json(array(
                                    "success" => false,
                                    "response" => self::$filesystemFailed,
                                    "status" => 403
                                ),
                                $this->callback
                            );
                        }
                    }else{
                        $this->setHeader('api', $this->requestFilextention, array());
                        $this->json(array(
                                "success" => false,
                                "response" => self::$directoryFailed,
                                "status" => 404
                            ),
                            $this->callback
                        );
                    }
                }else{
                    $this->setHeader('api', $this->requestFilextention, array());
                    $this->json(array(
                            "success" => false,
                            "response" => self::$appFailed,
                            "status" => 500
                        ),
                        $this->callback
                    );
                }

            break;

            //Handle the api documentation request (depends on availability of docs)

            case 'docs':

                $this->setHeader('docs', $this->requestFilextention, array());

                if($this->application)
                {
                    if($this->methodVerifier())
                    {
                        $this->application = new Application();
                        $call = $this->call;
                        $this->application->$call();
                    }else{
                        $this->application = new Application();
                        $this->application->docsError();
                    }

                }else{
                    $this->application = new Application();
                    $this->application->docsError();
                }

            break;
        }
    }

    /**
     * @param $params
     * @return bool
     */

    protected function loadParameters($params)
    {
        $this->parameters = new stdClass();

        $this->parameters->type = count($_POST) > 0 ? 'post' : 'get';

        if($this->parameters->type=='get')
        {
            $this->parameters->args = (object) $_GET;
            return true;
        }
        error_reporting(0);
        $parameters = json_decode($params);
        if(json_last_error() == JSON_ERROR_NONE)
        {
            return false;
        }
        $this->parameters->args = $parameters;

        return true;
    }

    /**
     * @return string
     */

    public function getRequestType()
    {
        if(count($_POST)>0&&$this->requestFilextention=='json')
        {
            return 'post';
        }elseif($this->requestFilextention=='html')
        {
            return 'docs';
        }elseif($this->requestFilextention!='html'&&$this->requestFilextention!='json')
        {
            return 'file';
        }else{
            return 'get';
        }
    }

    /**
     * @param $ext
     * @return string
     */

    public function extToMime($ext)
    {
        $res_array = array('gif' => 'image/gif', 'png' => 'image/png', 'jpg' => 'image/jpeg',
            'xml' => 'application/xml', 'json' => 'application/json', 'pdf' => 'application/pdf', 'zip' => 'application/zip',
            'txt' => 'text/plain', 'csv' => 'text/csv', 'html' => 'text/html', 'text/css',
            'avi' => 'video/avi', 'mp4' => 'video/mp4', 'ogg' => 'video/ogg', 'flv' => 'video/x-flv',
            'mp3' => 'audio/mpeg', 'wav' => 'audio/vnd.wave'
        );

        return array_key_exists($ext, $res_array) ? $res_array[$ext] : 'application/octet-stream';
    }

    /**
     * @param array $config
     * @param bool $callback
     */

    public function json(Array $config, $callback=FALSE)
    {
        if(isset($config['status']))
        {
            $this->setHTTPStatus($config['status']);
        }else{
            $this->setHTTPStatus(200);
        }

        ob_start();

        $json_encode = json_encode($config, JSON_PRETTY_PRINT);
        if($callback)
        {
            $json_value = $callback;
            $json_value .= '(';
            $json_value .= $json_encode;
            $json_value .= ');';
            echo $json_value;
            return;
        }
        echo $json_encode;
    }

    /*****************************************************************
     * @filesystem
     * Here is all methods to be used in internal or external file I/O
     *
     *****************************************************************/


    /**
     * @param $dir
     * @return array
     */

    protected function dirState($dir)
    {
        if(is_dir($dir))
        {
            if(file_exists($dir.'lock.dir'))
            {
                $file = file($dir.'lock.dir');
                if($file[1]==DIR_KEY)
                {
                    if(stristr($file[0], 'locked'))
                    {
                        return array('bool' => true, "locked" => true);
                    }else{
                        return array('bool' => true, "locked" => false);
                    }
                }else{
                    return array("bool" => false, "msg" => "Key doesn't match");
                }
            }else{
                return array("bool" => false, "msg" => "Directory state unknown");
            }
        }else{
            return array("bool" => false, "msg" => "Directory not found");
        }
    }

    /**
     * @param $dir
     * @param $state
     * @return bool
     */

    protected function createDir($dir, $state)
    {
        if(!is_dir($dir))
        {
            @mkdir($dir);
            //write a security handle file
            $fp = @fopen($dir.'lock.dir', 'w+');
            $writeln = $state;
            $writeln .= "\n";
            $writeln .= DIR_KEY;
            fputs($fp, $writeln);
            fclose($fp);
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $file
     * @return bool
     */

    protected function streamFile($file)
    {
        if(file_exists($file))
        {
            //ob_start();
            if(array_search($this->requestFilextention, array('gif', 'jpg', 'png'))!==FALSE&&isset($_SERVER['QUERY_STRING'])&&isset($_GET['resize']))
            {
                $static_image = 'system/images/static_image';
                $static_image .= md5(microtime(true) * 1000);
                $static_image .= '.';
                $static_image .= $this->requestFilextention;
                $fp = fopen($static_image, 'w+');

                $rm_file = file_get_contents($file);//implode('', file($file));

                fputs($fp, $rm_file);

                fclose($fp);

                $gd = $this->load('gd');

                $ratio = strstr($_GET['resize'], 'x') ? false : true;

                try {
                    $buffer = $gd->resizeImage($static_image, $_GET['resize'], $ratio);

                    unlink($static_image);

                    return $buffer;

                } catch (Exception $e) {

                    if(file_exists($static_image)) unlink($static_image);

                    $this->setHeader('api', $this->requestFilextention, array());
                    $this->json(array(
                            "success" => false,
                            "response" => $e->getMessage(),
                            "status" => 500
                        ),
                        $this->callback
                    );
                }


            }else{
                $fp = fopen($file, 'rb');
                while(!feof($fp))
                {
                    return fread($fp, filesize($file));
                }
                flush();
                ob_flush();
                fclose($fp);
            }

        }else{
            return false;
        }
    }

} 
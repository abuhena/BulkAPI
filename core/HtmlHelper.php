<?php
/**
 * Created by PhpStorm.
 * User: shaikot
 * Date: 6/28/14
 * Time: 6:05 PM
 */

//require '../system/configuration.php' OR die('system configuration failed to initialize');

class HtmlHelper {

    private $tags;
    private $html;
    private $cssBind;
    private $pageTitle;
    private $javascript;

    function __construct()
    {
        $this->pageTitle = "Documention @".$_SERVER['REQUEST_URI'];
    }

    /**
     * @param null $css
     * @return string
     */

    private function cssBinding($css=NULL)
    {
        $cssUnformatted = str_replace(';', ';'.PHP_EOL, $css);
        $this->cssBind = "<style type='text/css'>".PHP_EOL;
        $this->cssBind .= $cssUnformatted;
        $this->cssBind .= PHP_EOL;
        $this->cssBind .= "</style>";
        $this->cssBind .= PHP_EOL;

        return $this->cssBind;
    }

    /**
     * @return string
     */

    private function setTitle()
    {
        $use_title = "<title>".PHP_EOL;
        $use_title .= $this->pageTitle.PHP_EOL;
        $use_title .= "</title>".PHP_EOL;

        return $use_title;
    }

    /**
     * @return string
     */

    private function useMaster()
    {
        $master = 'system/styles/master.css';
        $fp = @fopen($master, 'rb');
        $read = fread($fp, filesize($master));
        fclose($fp);

        return $this->cssBinding($read);
    }

    /**
     * @return string
     */

    private function sendJavascript()
    {
        $this->javascript = htmlspecialchars($this->javascript);
        $script = "<script type='text/javascript' charset='utf-8'>".PHP_EOL;
        $script .= $this->javascript.PHP_EOL;
        $script .= "</script>";

        return $script;
    }

    /**
     * @param $js
     * @return string
     */

    private function externalJS($js)
    {
        $script = "<script type='text/javascript' charset='utf-8' src='";
        $script .= htmlspecialchars($js)."'>";
        $script .= "</script>".PHP_EOL;

        return $script;
    }

    /**
     * @param $args
     * @return string
     */

    private function Formatting($args)
    {
        $createElement = "<";
        $createElement .= $args->tag." ";
        foreach($args as $property=>$value)
        {
            if($property!='tag'&&$property!='text')
            {
                $createElement .= $property;
                $createElement .= "='".$value."' ";
            }
        }
        $createElement .= ">";
        $createElement .= PHP_EOL;
        $createElement .= $args->text;
        $createElement .= PHP_EOL;
        $createElement .= "</";
        $createElement .= $args->tag.">";
        $createElement .= PHP_EOL;

        return $createElement;
    }

    /**
     * @param $args
     * @return string
     */

    private function Input($args)
    {
        $createElement = "<input ";
        foreach($args as $property=>$value)
        {
            $createElement .= $property;
            $createElement .= "='".$value."' ";
        }
        $createElement .= "/>";
        $createElement .= PHP_EOL;
        $createElement .= PHP_EOL;

        return $createElement;
    }


    /**
     * @param $args
     * @return string
     */

    private function Div($args)
    {
        $createDiv = "<div ";

        foreach($args as $property=>$value)
        {
            if($property!='elements')
            {
                $createDiv .= $property;
                $createDiv .= "='".$value."' ";
            }
        }
        $createDiv .= ">";
        $createDiv .= PHP_EOL;

        if(isset($args->elements)&&is_object($args->elements))
        {
            foreach($args->elements as $method => $config)
            {
                $method = stristr($method, '[')&&stristr($method, ']') ? substr($method, 0, strpos($method, '[')) : $method;
                //echo $method;
                if(method_exists(__CLASS__, $method))
                {
                    $createDiv .= $this->$method($config);
                }
            }
        }else{
            $createDiv .= $args->elements;
        }

        $createDiv .= PHP_EOL;

        $createDiv .= "</div>";
        $createDiv .= PHP_EOL;

        return $createDiv;
    }

    /**
     * @param $args
     * @return string
     */

    private function Nav($args)
    {
        $createDiv = "<nav ";

        foreach($args as $property=>$value)
        {
            if($property!='elements')
            {
                $createDiv .= $property;
                $createDiv .= "='".$value."' ";
            }
        }
        $createDiv .= ">";
        $createDiv .= PHP_EOL;

        if(isset($args->elements)&&is_object($args->elements))
        {
            foreach($args->elements as $method => $config)
            {
                $method = stristr($method, '[')&&stristr($method, ']') ? substr($method, 0, strpos($method, '[')) : $method;
                //echo $method;
                if(method_exists(__CLASS__, $method))
                {
                    $createDiv .= $this->$method($config);
                }
            }
        }else{
            $createDiv .= $args->elements;
        }

        $createDiv .= PHP_EOL;

        $createDiv .= "</nav>";
        $createDiv .= PHP_EOL;

        return $createDiv;
    }


    /**
     * @param $args
     * @return string
     */

    private function oList($args)
    {
        $createList = "<li ";

        foreach($args as $property=>$value)
        {
            if($property!='elements')
            {
                $createList .= $property;
                $createList .= "='".$value."' ";
            }
        }
        $createList .= ">";
        $createList .= PHP_EOL;

        if(isset($args->elements)&&is_object($args->elements))
        {
            foreach($args->elements as $method => $config)
            {
                $method = stristr($method, '[')&&stristr($method, ']') ? substr($method, 0, strpos($method, '[')) : $method;
                //echo $method;
                if(method_exists(__CLASS__, $method))
                {
                    $createList .= $this->$method($config);
                }
            }
        }else{
            $createList .= $args->elements;
        }

        $createList .= PHP_EOL;

        $createList .= "</li>";
        $createList .= PHP_EOL;

        return $createList;
    }

/**
 * @param $args
 * @return string
 */

private function uList($args)
    {
        $createList = "<ul ";

        foreach($args as $property=>$value)
        {
            if($property!='elements')
            {
                $createList .= $property;
                $createList .= "='".$value."' ";
            }
        }
        $createList .= ">";
        $createList .= PHP_EOL;

        if(isset($args->elements)&&is_object($args->elements))
        {
            foreach($args->elements as $method => $config)
            {
                $method = stristr($method, '[')&&stristr($method, ']') ? substr($method, 0, strpos($method, '[')) : $method;
                //echo $method;
                if(method_exists(__CLASS__, $method))
                {
                    $createList .= $this->$method($config);
                }
            }
        }else{
            $createList .= $args->elements;
        }

        $createList .= PHP_EOL;

        $createList .= "</ul>";
        $createList .= PHP_EOL;

        return $createList;
    }


    /**
     * @param $args
     * @return string
     */

    private function Form($args)
    {
        $createForm = "<form ";

        foreach($args as $property=>$value)
        {
            if($property!='elements')
            {
                $createForm .= $property;
                $createForm .= "='".$value."' ";
            }
        }
        $createForm .= ">";
        if(isset($args->elements)&&is_object($args->elements))
        {
            foreach($args->elements as $method => $config)
            {
                $method = stristr($method, '[')&&stristr($method, ']') ? substr($method, 0, strpos($method, '[')) : $method;
                if(method_exists(__CLASS__, $method))
                {
                    $createForm .= $this->$method($config);
                }
            }
        }else{
            $createForm .= $args->elements;
        }

        $createForm .= "</form>";

        return $createForm;
    }

    /**
     * @param array $config
     * @throws Exception
     */

    public function htmlBind(Array $config)
    {
        $this->tags = $config;
        if(array_key_exists('html', $this->tags))
        {
            $this->tags = json_decode(json_encode($this->tags));
            //print_r($this->tags); die;
            $this->html = '<!DOCTYPE html>'.PHP_EOL;
            $this->html .= '<html lang="en">'.PHP_EOL;
            $this->html .= PHP_EOL;
            if(array_key_exists('head', (array)$this->tags->html))
            {
                $this->html .= "<head>";
                $this->html .= PHP_EOL;
                $this->html .= '<meta name="viewport" content="width=device-width, initial-scale=1">';
                $this->html .= PHP_EOL;
                $this->html .= (array_key_exists('master_css', (array)$this->tags->html->head)) ? $this->useMaster() : null;
                $this->html .= PHP_EOL;
                $this->pageTitle = (array_key_exists('title', (array)$this->tags->html->head)) ? $this->tags->html->head->title : null;
                $this->html .= PHP_EOL;
                $this->html .= ($this->pageTitle!=null) ? $this->setTitle() : null;
                $this->html .= PHP_EOL;
                $this->javascript .= (array_key_exists('javascript', (array)$this->tags->html->head)) ? $this->tags->html->head->javascript : null;
                $this->html .= ($this->javascript!=null) ? $this->javascript : null;
                $this->html .= (array_key_exists('externalJS', (array)$this->tags->html->head)) ? $this->tags->html->head->externalJS : null;
                $this->html .= PHP_EOL;
                $this->html .= "</head>";
                $this->html .= PHP_EOL;
                $this->html .= PHP_EOL;
                if(array_key_exists('body', (array)$this->tags->html))
                {
                    $this->setBody();
                }else{
                    throw new Exception('<h1>body</h1> <h2>parameter missing</h2>');
                    break;
                }
            }else{
                throw new Exception('<h1>head</h1> <h2>paramter missing</h2>');
                break;
            }

            $this->html .= "</body>";
            $this->html .= PHP_EOL;
            $this->html .= "</html>";

        }else{
            throw new Exception('<h1>html</h1> <h2>paramter missing</h2>');
            break;
        }

        echo $this->html;
    }

    /**
     *
     */

    private function setBody()
    {
        $this->html .= "<body>";
        $this->html .= PHP_EOL;
        $this->callElements($this->tags->html->body);
    }

    /**
     * @param $elements
     */

    private function callElements($elements)
    {
        foreach($elements as $key => $value)
        {
            $key = stristr($key, '[')&&stristr($key, ']') ? substr($key, 0, strpos($key, '[')) : $key;
            if(method_exists(__CLASS__, $key))
            {
                $this->html .= $this->$key($value);
            }
        }
    }


}
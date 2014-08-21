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

class UploadHandler {

    private $file;
    private $file_name;
    private $file_save_name;
    private $file_size;
    private $file_extension;

    private $path_to_upload;

    private static  $DEFAULT_FILE_SAVE_PATH = 'users/upload_files/';
    private static  $DEFAULT_ACCESS_DIRECTORY = 'users/';

    function __construct() {}

    /**
     * @param $file
     * @param null $path
     * @param null $resolution
     * @param null $name
     * @param int $max
     * @throws Exception
     */

    public function imageUpload($file, $path=NULL, $resolution=NULL, $name=NULL, $max=1024)
    {
        $this->file = $file;
        $this->file_size = $this->file['size'];
        $this->file_name = is_null($name) ? $this->file['tmp_name'] : htmlspecialchars($name);

        $mime2ext = array('image/jpeg' => 'jpg', 'image/gif' => 'gif', 'image/png' => 'png');

        if(($this->file_size / 1024)<=$max)
        {
            $this->path_to_upload = $this->getSavePath($path);
            $image_info = getimagesize($this->file['tmp_name']);
            if($image_info)
            {
                $image_xy = ($resolution==NULL) ? $image_info[0] .'x'. $image_info[1] :
                    $this->getImageResolution($resolution);

                $this->file_extension = $mime2ext[$image_info['mime']];
                $this->file_name = $this->generateName($name);


                $static_image = 'system/images/static_image';
                $static_image .= md5(microtime(true) * 1000);
                $static_image .= '.';
                $static_image .= $this->file_extension;

                $tmp_file = file_get_contents($this->file['tmp_name']);

                file_put_contents($static_image, $tmp_file);

                $gd = new GDBasic();
                ob_start();
                $binery_data = $gd->resizeImage($static_image, $image_xy, false);
                $buffer = ob_get_clean();

                $this->save($buffer);

                unlink($static_image);

            }else{
                throw new Exception('The system is failed to recognize the file as Image File');
            }
        }else{
            throw new Exception('Max file size exceeded the limit. -');
        }

    }

    /**
     * @param $file
     * @param $path
     * @param $name
     * @param int $max
     * @throws Exception
     */

    public function commonUpload($file, $path, $name, $max=1024)
    {
        $this->file = $file;
        $this->file_size = $this->file['size'];

        if(($max / 1024)<=$this->file_size)
        {

        }else{
            throw new Exception('Max file size exceeded the limit.');
        }
    }


    /**
     * @return string
     */

    public function getLastFileUri()
    {
        $name = $this->path_to_upload;
        $name .= $this->file_name;
        $name .= '.';
        $name .= $this->file_extension;

        return $name;
    }

    /**
     *
     */

    public function getLastFileUrl()
    {
        $name = $_SERVER['REQUEST_SCHEME'];
        $name .= '://';
        $name .= $_SERVER['HTTP_HOST'];
        $name .= substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/'));
        $name .= '/';
        $name .= $this->path_to_upload;
        $name .= $this->file_name;
        $name .= '.';
        $name .= $this->file_extension;

        return $name;
    }

    /**
     * @param $dimen
     * @return array
     * @throws Exception
     */

    private function getImageResolution($dimen)
    {
        if(is_string($dimen)&&strstr($dimen, 'x'))
        {
            return $dimen;
        }else{
            throw new Exception('Resolution parameter has an invalid format.');
        }
    }

    /**
     *
     */

    private function generateName($name)
    {
        $file_name = substr($this->file['name'], 0, strrpos($this->file['name'], '.'));

        if(is_null($name))
        {
            $hash = md5(time());
            $name .= $hash;
            $name .= '_';
            $name .= $file_name;

            $name = str_replace(' ', '_', $name);

            return $name;
        }else{
            if(file_exists($this->path_to_upload.$name))
            {
                throw new Exception('Can not perform an upload - file name already exists');
            }else{
                return $file_name;
            }
        }
    }

    /**
     * @param $path
     * @return string
     */

    private function getSavePath($path)
    {
        if($path==NULL)
        {
            return self::$DEFAULT_FILE_SAVE_PATH;
        }else{
            $started = self::$DEFAULT_ACCESS_DIRECTORY;
            $paths = explode('/', $path);
            for($i=0; count($paths)>$i; $i++)
            {
                $started .= $paths[$i];
                if(!is_dir($started))
                {
                    mkdir($started);
                }
                $started .= '/';
            }
            //print_r($started); die;
            return $started;
        }
    }

    private function save($bineryData=NULL)
    {
        $pack = $this->path_to_upload;
        $pack .= $this->file_name;
        $pack .= '.';
        $pack .= $this->file_extension;

        if($bineryData==null)
        {
            return move_uploaded_file($this->file['tmp_name'], $pack);
        }else{
            $fp = @fopen($pack, 'w+');
            fputs($fp, $bineryData);
            fclose($fp);
            unlink($this->file['tmp_name']);
        }
    }
}
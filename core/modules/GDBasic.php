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

class GDBasic {

    private $image_uri;
    private $resource;
    private $second_resource;
    private $image_type;
    private $height;
    private $width;
    private $resizeX;
    private $resizeY;
    private $resizeRatio;

    private static $open_error;

    function __construct()
    {
        $this->resizeRatio = false;
        self::$open_error = "The given resource is NOT valid associative image format!";
    }

    private function open()
    {
        $getExt = substr($this->image_uri, (strripos($this->image_uri, '.') + 1));
        if(strstr($getExt, '?'))
        {
            $getExt = substr($getExt, strpos($getExt, '?'));
        }
        //echo $getExt; die;
        if(array_search(strtolower($getExt), array('jpg', 'png', 'gif'))!==FALSE)
        {
            $this->image_type = $getExt;

            switch ($this->image_type)
            {
                case 'gif':

                    $this->resource = @imagecreatefromgif($this->image_uri);

                break;

                case 'jpg':

                    $this->resource = @imagecreatefromjpeg($this->image_uri);

                break;

                case 'png':

                    $this->resource = @imagecreatefrompng($this->image_uri);

                break;

                default :

                    throw new Exception('The resource of image file is corrupted');

                break;
            }
            if(!$this->resource)
            {
                throw new Exception(self::$open_error);
            }
        }else{
            throw new Exception('Unable to open image file.');
        }
    }

    private function getRatioHeight()
    {
        $sub = ( $this->width - $this->resizeX);

        if($sub > 0)
        {
            $width_bulk = ( $this->width / 100 );

            $algo_ratio = $sub / $width_bulk;

            $getHeightToCut = (( $this->height * $algo_ratio) / 100 );

            $this->resizeY = ( $this->height - $getHeightToCut );
        }else{
            throw new Exception('You can not upgrade image size in ratio resize mode');
        }
    }

    public function resizeImage($image, $resizeXY, $resizeRatio)
    {
        $this->image_uri = $image;

        $this->open();

        list($width, $height) = getimagesize($this->image_uri);

        $this->width = $width;

        $this->height = $height;

        if($resizeRatio)
        {
            $this->resizeX = $resizeXY;
            if(is_numeric($this->resizeX))
            {
                $this->resizeRatio = true;

                $this->getRatioHeight();

            }else{

                throw new Exception('Image resize defination should be numeric and solid Integer, NaN given');

            }
           }else{

            list($x, $y) = explode('x', $resizeXY);

            if((is_numeric($x) && $x > 0) && (is_numeric($y) && $y >0))
            {
                $this->resizeX = $x;

                $this->resizeY = $y;
            }else{
                throw new Exception('Image resize defination invalid - it should be structured as WWxHH and of course WW/HH should have integer value.');
            }
        }

        $this->openSecondary();

        imagecopyresampled($this->second_resource, $this->resource, 0, 0, 0, 0, $this->resizeX, $this->resizeY, $this->width, $this->height);

        return $this->getBineryImageData($this->second_resource);
    }

    private function openSecondary()
    {
        $this->second_resource = imagecreatetruecolor($this->resizeX, $this->resizeY);

        return $this->second_resource;
    }

    private function getBineryImageData($res)
    {
        switch ($this->image_type)
        {
            case 'gif':

                return imagegif($res);

                break;

            case 'jpg':

                return imagejpeg($res, null, 100);

                break;

            case 'png':

                return imagepng($res, null, 9);

                break;

            default :

                throw new Exception('The resource of image file is corrupted ( Can not get binery data )');

                break;
            }
    }

}
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


class GCMmodule {

    private $apiKey;
    private $deviceKeys;
    private $data;

    /**
     *
     */
    public function __construct()
    {
        $this->apiKey = GCM_API_KEY;
        if(empty($this->apiKey))
        {
            throw new Exception('GCM API Key doesn\'t appears to setup yet! Please check configuartion file.');
        }
    }

    /**
     * @param $registrationIds
     * @param $data
     * @param string $method
     * @throws Exception
     */

    public function send($registrationIds, $data, $method='http')
    {
        if(!is_array($registrationIds))
        {
            throw new Exception('Device registration IDs should pass through as Array - string given');
        }elseif(!is_array($data))
        {
            throw new Exception('Message data should pass through as Array - string given');
        }else{

            $this->deviceKeys = $registrationIds;
            $this->data = $data;

            if($method=='http')
            {
                $this->sendHTTP();
            }else{
                $this->sendStream();
            }
        }
    }

    /**
     * @return mixed
     */

    private function sendHTTP()
    {
        $headers = array("Content-Type:" . "application/json", "Authorization:" . "key=" . $this->apiKey);
        $data = array(
            'data' => $this->data,
            'registration_ids' => $this->deviceKeys
        );

        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send" );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($data) );

        $response = curl_exec($ch);
        curl_close($ch);
        $json_decode = json_decode($response, true);
        return $json_decode;
    }

    /**
     * @return mixed
     */

    private function sendStream()
    {

        $data = array(
            'data' => $this->data,
            'registration_ids' => $this->deviceKeys
        );

        $stream_context = array (
            'ssl' => array(
                'verify_peer'   => false
            ),
            'http' => array (
                'method' => 'POST',
                'header'=> array("Content-Type:" . "application/json", "Authorization:" . "key=" . $this->apiKey),
                'content' => json_encode($data)
            )
        );

        $context = stream_context_create($stream_context);

        $open = file_get_contents('https://android.googleapis.com/gcm/send', false, $context);

        $json_decode = json_decode($open, true);
        return $json_decode;
    }
}
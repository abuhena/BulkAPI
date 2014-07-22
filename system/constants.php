<?php
/**
 * Created by PhpStorm.
 * User: shaikot
 * Date: 6/28/14
 * Time: 3:21 PM
 */

define('APIKey', isset($_REQUEST['api_key']) ? $_REQUEST['api_key'] : 'fake key');
define('PATH_TO_CONFIG', 'system/configuration.php');
define('PATH_TO_APIKEYS', 'system/apiKeys.php');
define('PATH_TO_USERFUNC', 'application/app.php');
define('PATH_TO_RESTCLIENT', 'core/RestClient.php');
define('PATH_TO_HTMLHELPER', 'core/HtmlHelper.php');
define('PATH_TO_MYSQL', 'core/MySQLmodule.php');
define('PATH_TO_GCM', 'core/GCMmodule.php');
define('PATH_TO_ANALYTICS', 'core/Analytics.php');

define('DIR_KEY', md5('Hello, World!'));
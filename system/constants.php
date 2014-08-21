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
define('PATH_TO_USERFUNC', 'application/Application.php');
define('PATH_TO_RESTCLIENT', 'core/RestClient.php');
define('PATH_TO_BULKAPI', 'core/BulkAPI.php');
define('PATH_TO_HTMLHELPER', 'core/modules/HtmlHelper.php');
define('PATH_TO_MYSQL', 'core/modules/MySQLmodule.php');
define('PATH_TO_GCM', 'core/modules/GCMmodule.php');
define('PATH_TO_ANALYTICS', 'core/modules/Analytics.php');
define('PATH_TO_GDBASIC', 'core/modules/GDBasic.php');
define('PATH_TO_STRINGEXPRESS', 'core/modules/StringExpress.php');
define('PATH_TO_SESSIONMODULE', 'core/modules/SessionModule.php');
define('PATH_TO_SESSIONSAVEHANDLER', 'core/modules/SessionSaveHandler.php');
define('PATH_TO_UPLOADER', 'core/modules/Uploader.php');

define('DIR_KEY', md5('Hello, World!'));
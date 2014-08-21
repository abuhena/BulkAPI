<?php
/**
 * Created by PhpStorm.
 * User: shaikot
 * Date: 7/3/14
 * Time: 10:59 PM
 */

require_once 'constants.php';
require_once PATH_TO_HTMLHELPER;
require_once PATH_TO_MYSQL;
require_once PATH_TO_ANALYTICS;
require_once PATH_TO_GCM;
require_once PATH_TO_GDBASIC;
require_once PATH_TO_STRINGEXPRESS;
require_once PATH_TO_SESSIONMODULE;
require_once PATH_TO_SESSIONSAVEHANDLER;
require_once PATH_TO_UPLOADER;

/**
 * Application Settings
 */

define('USE_BASIC_ANALYTICS', TRUE);

/**
 * MySQL configuration
 * CAUTION: if MySQL module called except setting up mysql connection
 * it will throw a regular exception, when using/loading mysql module - recommended to use try/catch block
 * and print the exception.
 */

define('DB_USER', 'root'); // MySQL username
define('DB_PASSWORD', 'result1244'); // MySQL server password
define('DB_HOST', 'localhost'); // MySQL server host
define('DB_DATABASE', 'test2'); // MySQL database to be used

/**
 * Google Cloud Messaging
 */

define('GCM_API_KEY', '');

/**
 * Use Custom Session Save Handler (TRUE to activate)
 * You should use MySQL Database to activate this section
 * CAUTION: The framework will stop working either unaccesible MySQLi connection
 * or table creation problem (if issued) when using Session module
 * The custom session system create a MySQL table : Session_Handler
 * The System can oparate it frequently (so be careful for low-memory lackage)
 */

define('CUSTOM_SESSION_SAVE_HANDLER', TRUE); //Activate the system

define('SESSION_STORAGE_TABLE_NAME', 'Session_Handler'); //if your db server already has a table name 'Session_Handler' then change it with your choise
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

/**
 * Application Settings
 */

define('USE_BASIC_ANALYTICS', TRUE);

/**
 * MySQL configuration
 */

define('DB_USER', 'root'); // MySQL username
define('DB_PASSWORD', 'result1244'); // MySQL server password
define('DB_HOST', 'localhost'); // MySQL server host
define('DB_DATABASE', 'test2'); // MySQL database to be used

/**
 * Google Cloud Messaging
 */

define('GCM_API_KEY', '');
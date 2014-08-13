<?php

include 'system/configuration.php';
//include PATH_TO_HTMLHELPER;
include_once PATH_TO_RESTCLIENT;
include_once PATH_TO_BULKAPI;
include_once PATH_TO_USERFUNC;

$class = new Application();

$class->parseUri();
$class->request($class->getRequestType(), $class->requestFilename, $_REQUEST);


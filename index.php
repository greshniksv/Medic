<?php

$AJAX=0;
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') $AJAX=1;

if($AJAX==0){
?>

<html>
<head>
    <title>Medic</title>
    <meta charset="UTF-8">
</head>

<link rel="stylesheet" href="Css/main.css">
<link rel="stylesheet" href="Helper/jquery-ui-1.11.1.custom/jquery-ui.min.css">
<link rel="stylesheet" href="Helper/bootflat.github.io-master/css/bootstrap.min.css">

<script src="Helper/jquery-2.1.1.min.js"></script>
<script src="Helper/bootflat.github.io-master/js/bootstrap.min.js"></script>
<script src="Helper/jquery-ui-1.11.1.custom/jquery-ui.min.js"></script>

<body>
<?php
}

require_once 'config.php';
require_once 'Helper/Database.php';
require_once 'Helper/UUID.php';
require_once 'Helper/logging.php';
require_once 'Helper/Mvc.php';

$controller = $_REQUEST["c"];
$session = $_REQUEST["s"];
$action = $_REQUEST["a"];

if(strlen($controller)<1) $controller="Home";
if(strlen($action)<1) $action="index";
if(strlen($session)<1) $session="none";

$db = new Database($DB_HOST, $DB_NAME, $DB_USER, $DB_PASS);
$db -> Connect();

$log = new Logging($db,$session);

$log->Write("index","Togo controller: ".$controller);


include "Controller/".$controller.".php";

if($AJAX==0){
?>
</body>
</html>
<?php } ?>
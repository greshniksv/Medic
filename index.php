<?php
require_once 'config.php';
require_once 'Helper/Database.php';
require_once 'Helper/UUID.php';
require_once 'Helper/logging.php';

$controller = $_REQUEST["c"];
$session = $_REQUEST["s"];

if(strlen($controller)<1) $controller="Home";
if(strlen($session)<1) $session="none";

$db = new Database($DB_HOST, $DB_NAME, $DB_USER, $DB_PASS);
$db -> Connect();

$log = new Logging($db,$session);

$log->Write("index","Togo controller: ".$controller);


include "Controller/".$controller.".php";


?>
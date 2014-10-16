<?php
ini_set('display_errors', 1);
error_reporting(~0);

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

<link rel="stylesheet" href="Helper/jquery-ui-1.11.1.custom/jquery-ui.css">
<link rel="stylesheet" href="Helper/jquery-ui-1.11.1.custom/jquery-ui.theme.css">
<link rel="stylesheet" href="Helper/bootstrap-3.2.0-dist/css/bootstrap.min.css">
<link rel="stylesheet" href="Helper/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="Helper/dataTables.bootstrap.css">

<script src="Helper/jquery-2.1.1.min.js"></script>
<script src="Helper/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
<script src="Helper/jquery-ui-1.11.1.custom/jquery-ui.min.js"></script>
<script src="Helper/dataTables.bootstrap.js"></script>
<script src="Helper/jquery.dataTables.min.js"></script>
<script src="Helper/dataTables.tableTools.min.js"></script>
<script src="Helper/dataTables.editor.min.js"></script>
<script src="Helper/jquery.collapse.js"></script>
<script src="Helper/jquery.fileupload.js"></script>


<body>
<?php
}

require_once 'config.php';
require_once 'Helper/Database.php';
require_once 'Helper/UUID.php';
require_once 'Helper/logging.php';
require_once 'Helper/Mvc.php';
require_once 'Helper/Session.php';
require_once 'Helper/ProcessPriceWorker.php';
require_once 'Helper/Permission.php';



$db = new Database($DB_HOST, $DB_NAME, $DB_USER, $DB_PASS);
$db -> Connect();

$controller = @$_REQUEST["c"];
$action = @$_REQUEST["a"];
$cookie = @$_COOKIE["session"];
$permission=Permission::Get();


if(strlen($controller)<1)
{
    if(!Session::IsActiveSession($cookie))
    {
        $controller="Login";
    }
    else
    {
        $controller="Home";
    }
}

if(strlen($action)<1) $action="index";
$log = new Logging($db,$cookie);
//$log->Write("index","Перешел на страницу: ".$controller);

include "Controller/".$controller.".php";

if($AJAX==0){
?>
</body>
</html>
<?php } ?>
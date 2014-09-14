<?php

switch ($action) {

    // ACTION =========================
    case "login":
        $log = $_REQUEST["login"];
        $pas = $_REQUEST["password"];

        $pas = stripslashes($pas);
        $log = trim(preg_replace("/[^a-zA-Z0-9_\-]+/", "", $log));
        $pas = trim(preg_replace("/[^a-zA-Z0-9_\-]+/", "", $pas));
        if (strlen($log) < 1) die("Incorrect login!");
        if (strlen($pas) < 1) die("Incorrect password!");

        $d = $db->QueryOne("select Password,Hash,id from Users where login = '{$log}' ");
        if ($d["Hash"] == '0') {

            if ($d["Password"] != $pas) {
                echo "no";
                exit();
            }
        } else {
            if (crypt($d["Password"], "bla bla bla +-*/ ☺") != $pas) {
                echo "no";
                exit();
            }
        }
        $sessionid = GenSession($d["id"]);
        setcookie("session", $sessionid, time()+3600);

        die("ok");
        break;







    default:
        echo "Controller not found";
        break;
}

function GenSession($userid)
{
    global $db;

    $guid = UUID::v4();
    $date = date("Y-m-d H:i:s");
    $dateExt   = new DateTime;
    $dateExt->modify( '+1 hour' );
    $def = $dateExt->format("Y-m-d H:i:s");

    $db->Exec("INSERT INTO `Session`(`id`,`UserId`,`CreateDate`,`ExpireDate`)
                      VALUE ('{$guid}','{$userid}','{$date}','{$def}'); ");

    return $guid;
}



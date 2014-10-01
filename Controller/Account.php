<?php

switch ($action) {


    // ACTION =========================
    case "login":
        $log = $_REQUEST["login"];
        $pas = $_REQUEST["password"];
        Session::DeleteUnusedSession();

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
            if (crypt($pas, "mc05wBF&IТПШРnw4ton*R +-*/ ☺") !=$d["Password"] ) {
                echo "no";
                exit();
            }
        }
        $sessionid = Session::GenId($d["id"]);
        setcookie("session", $sessionid, time()+3600);

        die("ok");
        break;




    case "logout":
        setcookie('session', null, -1);
        die("ok");
        break;



    default:
        echo "Action not found";
        break;
}





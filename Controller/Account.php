<?php

switch ($action) {


    // ACTION =========================
    case "login":
        $login = $_REQUEST["login"];
        $pas = $_REQUEST["password"];
        Session::DeleteUnusedSession();

        $log->Write(basename(__FILE__,".php"),"Вход в систему: ".$login);

        $pas = stripslashes($pas);
        $login = trim(preg_replace("/[^a-zA-Z0-9_\-]+/", "", $login));
        $pas = trim(preg_replace("/[^a-zA-Z0-9_\-]+/", "", $pas));
        if (strlen($login) < 1) die("Логин или пароль не верен!");
        if (strlen($pas) < 1) die("Логин или пароль не верен!");

        $d = $db->QueryOne("select Password,Hash,id,Permission from Users where login = '{$login}' ");

        $r = $db->QueryOne("select Value from Options where Param='Active' ");
        if($r["Value"]!="true" && $d["Permission"]=="2")
        {
            die("Сайт временно не доступен!<br>Обновляется база данных.");
        }

        if ($d["Hash"] == '0') {

            if ($d["Password"] != $pas) {
                die("Логин или пароль не верен!");
            }
        } else {
            if (crypt($pas, "mc05wBF&IТПШРnw4ton*R +-*/ ☺") !=$d["Password"] ) {
                die("Логин или пароль не верен!");
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





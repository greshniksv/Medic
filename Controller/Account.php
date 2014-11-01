<?php

switch ($action) {


    case "check_session":
            if(!Permission::CheckSession()) die("failed"); else die("alive");
        break;

    case "send_password":
        $login = $_REQUEST["login"];
        $new_pas = Session::GenPassword($login,6);

        $user = $db->QueryOne("select Password,Hash,id,Permission,Mail from Users where login = '{$login}' ");
        if(strlen($user["Mail"])<2)
        {
            $log->Write(basename(__FILE__,".php"),"Почтовый адрес не найден.");
            die("Почтовый адрес не найден!");
        }

        $date = date("Y-m-d H:i:s");
        $log->Write(basename(__FILE__,".php"),"Отправка письма на ".$user["Mail"]);
        if(Mail::Send($user["Mail"],"Пароль ({$date})","<br /><br />Пароль для входа в систему: ".$new_pas))
        {
            die("ok");
        }else
        {
            $log->Write(basename(__FILE__,".php"),"Ошибка отправки письма.");
            die("Ошибка отправки письма!");
        }
        break;

    // ACTION =========================
    case "login":
        $login = $_REQUEST["login"];
        $pas = $_REQUEST["password"];
        Session::DeleteUnusedSession();
        Session::DeleteUnusedPasswords();

        $log->Write(basename(__FILE__,".php"),"Вход в систему: ".$login);

        $pas = stripslashes($pas);
        $login = trim(preg_replace("/[^a-zA-Z0-9_\-]+/", "", $login));
        $pas = trim(preg_replace("/[^a-zA-Z0-9_\-]+/", "", $pas));
        if (strlen($login) < 1) die("Логин или пароль не верен!");
        if (strlen($pas) < 1) die("Логин или пароль не верен!");

        $user = $db->QueryOne("select Password,Hash,id,Permission from Users where login = '{$login}' ");

        if(Session::AlreadyLogin($user["id"]))
        {
            $log->Write(basename(__FILE__,".php"),"ВНИМАНИЕ! Повторная авторизация.");
            die("Пользователь с вышим логином уже в системе!");
        }

        $r = $db->QueryOne("select Value from Options where Param='Active' ");
        if($r["Value"]!="true" && $user["Permission"]=="2")
        {
            $log->Write(basename(__FILE__,".php"),"Попытка войти на заблокированный сайт");
            die("Сайт временно не доступен!<br>Обновляется база данных.");
        }

        // For administrators
        if($user["Permission"]=="0"){
            if ($user["Hash"] == '0') {

                if ($user["Password"] != $pas) {
                    $log->Write(basename(__FILE__,".php"),"Неверная авторизация");
                    die("Логин или пароль не верен!");
                }
            } else {
                if (crypt($pas, "mc05wBF&IТПШРnw4ton*R +-*/ ☺") !=$user["Password"] ) {
                    $log->Write(basename(__FILE__,".php"),"Неверная авторизация");
                    die("Логин или пароль не верен!");
                }
            }
            $sessionid = Session::GenId($user["id"]);
            setcookie("session", $sessionid, time()+900);
        }
        else
        {
            // For Users with mail
            $p = $db->QueryOne("select Password from `TemporaryPasswords` where UserId='".$user["id"]."' order by DateTime desc");
            if($p["Password"]!=$pas)
            {
                $log->Write(basename(__FILE__,".php"),"Неверная авторизация");
                die($p["Password"]."-".$pas." Логин или пароль не верен!");
            }
            else
            {
                $sessionid = Session::GenId($user["id"]);
                setcookie("session", $sessionid, time()+900);
                Session::DeletePasswords($login);
            }
        }

        die("ok");
        break;




    case "logout":
        $log->Write(basename(__FILE__,".php"),"Выход");
        Session::Delete($cookie);
        setcookie('session', null, -1);
        die("ok");
        break;



    default:
        echo "Action not found";
        break;
}





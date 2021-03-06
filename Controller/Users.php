<?php

switch ($action) {

    case "index":
        Mvc::View(basename(__FILE__,".php"));
        break;

    case "UserList":
        Mvc::View(basename(__FILE__,".php"),"list");
        break;

    case "GetIdByLogin":
        $log = $_REQUEST["login"];
        $r = $db->QueryOne("select id from Users where Login='{$log}' ");
        die($r["id"]);
        break;


    case "UserData":
        $db->Query("select id, Login, FirstName, LastName,Permission, Mail from Users order by Login");
        while($buf=$db->Fetch())
        {
            $data[]=array("id"=>$buf["id"],"Login"=>$buf["Login"],"FirstName"=>$buf["FirstName"],
                "LastName"=>$buf["LastName"],"Permission"=>$buf["Permission"],"Mail"=>$buf["Mail"]);
        }
        $db->StopFetch();

        if($data==null)
            die("{\"data\": []}");

        die("{\"data\": ".json_encode($data)."}");
        break;

    // ACTION =========================
    case "Create":
        $login = $_REQUEST["login"];
        $pas = $_REQUEST["password"];
        $fir = $_REQUEST["firstname"];
        $las = $_REQUEST["lastname"];
        $per = $_REQUEST["permission"];
        $mail = $_REQUEST["mail"];

        $log->Write(basename(__FILE__,".php"),"Добавление пользователя:".$login);

        $hpass = crypt($pas, "mc05wBF&IТПШРnw4ton*R +-*/ ☺");
        $guid = UUID::v4();

        // permission: 0-admin,1-uploader,2-searcher

        $sql = " INSERT INTO `Users` (`id`,  `Login`,  `Password`,  `Hash`,  `FirstName`,  `LastName`,  `Permission`, `Mail`)".
                " VALUE ('{$guid}','{$login}','{$hpass}','1','{$fir}','{$las}','{$per}','{$mail}');";

        if(!$db->Exec($sql))
        {
            die("Error add user!");
        }

        die("ok");
        break;



    case "Edit":
        $userid = $_REQUEST["userid"];
        $login = $_REQUEST["login"];
        $pas = $_REQUEST["password"];
        $fir = $_REQUEST["firstname"];
        $las = $_REQUEST["lastname"];
        $per = $_REQUEST["permission"];
        $mail = $_REQUEST["mail"];
        $log->Write(basename(__FILE__,".php"),"Изменение пользователя:".$login);

        $hpass = crypt($pas, "mc05wBF&IТПШРnw4ton*R +-*/ ☺");
        $guid = UUID::v4();

        // permission: 0-admin,1-uploader,2-searcher
        $sql = " update `Users` set `Login`='{$login}',".($pas==""?"":"`Password`='{$hpass}',").
            " `Hash`='1',`FirstName`='{$fir}',`LastName`='{$las}' ,`Permission`='{$per}',`Mail`='{$mail}'  where `id`='{$userid}'";

        if(!$db->Exec($sql))
        {
            die("Error update user!");
        }

        die("ok");
        break;



    case "Delete":

        $userid = $_REQUEST["userid"];
        $r = $db->QueryOne("select Login from Users where id='{$userid}' ");
        $log->Write(basename(__FILE__,".php"),"Удаление пользователя:".$r["Login"]);

        // permission: 0-admin,1-uploader,2-searcher
        $sql = " delete from `Users` where `id`='{$userid}'";

        if(!$db->Exec($sql))
        {
            die("Error delete user!");
        }

        die("ok");
        break;

    default:
        echo "Action not found";
        break;
}



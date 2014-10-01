<?php

switch ($action) {

    case "index":
        Mvc::View(basename(__FILE__,".php"));
        break;

    case "UserList":
        Mvc::View(basename(__FILE__,".php"),"userlist");
        break;

    case "GetIdByLogin":
        $log = $_REQUEST["login"];
        $r = $db->QueryOne("select id from Users where Login='{$log}' ");
        die($r["id"]);
        break;


    case "UserData":
        $db->Query("select id, Login, FirstName, LastName,Permission from Users order by Login");
        //$data[]=Array();
        while($buf=$db->Fetch())
        {
            $data[]=array("id"=>$buf["id"],"Login"=>$buf["Login"],"FirstName"=>$buf["FirstName"],
                "LastName"=>$buf["LastName"],"Permission"=>$buf["Permission"],);
        }
        $db->StopFetch();

        echo "{\"data\": ".json_encode($data)."}";
        //print_r($data);
        die();
        break;

    // ACTION =========================
    case "Create":
        $log = $_REQUEST["login"];
        $pas = $_REQUEST["password"];
        $fir = $_REQUEST["firstname"];
        $las = $_REQUEST["lastname"];
        $per = $_REQUEST["permission"];

        $hpass = crypt($pas, "mc05wBF&IТПШРnw4ton*R +-*/ ☺");
        $guid = UUID::v4();

        // permission: 0-admin,1-uploader,2-searcher

        $sql = " INSERT INTO `Users` (`id`,  `Login`,  `Password`,  `Hash`,  `FirstName`,  `LastName`,  `Permission`)".
                " VALUE ('{$guid}','{$log}','{$hpass}','1','{$fir}','{$las}','{$per}');";

        if(!$db->Exec($sql))
        {
            die("Error add user!");
        }

        die("ok");
        break;



    case "Edit":

        $userid = $_REQUEST["userid"];
        $log = $_REQUEST["login"];
        $pas = $_REQUEST["password"];
        $fir = $_REQUEST["firstname"];
        $las = $_REQUEST["lastname"];
        $per = $_REQUEST["permission"];

        $hpass = crypt($pas, "mc05wBF&IТПШРnw4ton*R +-*/ ☺");
        $guid = UUID::v4();

        // permission: 0-admin,1-uploader,2-searcher
        $sql = " update `Users` set `Login`='{$log}',".($pas==""?"":"`Password`='{$hpass}',").
            " `Hash`='1',`FirstName`='{$fir}',`LastName`='{$las}' ,`Permission`='{$per}'  where `id`='{$userid}'";

        if(!$db->Exec($sql))
        {
            die("Error update user!");
        }

        die("ok");
        break;



    case "Delete":

        $userid = $_REQUEST["userid"];

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



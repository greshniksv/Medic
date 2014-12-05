<?php

// город, Адрес, контактный лелефон, фио

switch($action)
{
    // ACTION =========================
    case "index":
        Mvc::View(basename(__FILE__,".php"));
        break;

    case "info":
        $id = $_REQUEST["id"];
        $buf = $db->QueryOne("select id,Name,FullName,City,Address,Phone,IIN,InitialPrice from Provider where id='$id' ");
        $data = array("id"=>$buf["id"],"Name"=>$buf["Name"],"FullName"=>$buf["FullName"],
            "City"=>$buf["City"],"Address"=>$buf["Address"],"Phone"=>$buf["Phone"],"IIN"=>$buf["IIN"],
            "InitialPrice"=>$buf["InitialPrice"]);

        Mvc::View(basename(__FILE__,".php"),"info",$data);
        break;

    case "get_list_data":
        $db->Query("select id, Name,FullName,City,Address,Phone,IIN from Provider order by Name desc");
        while($buf=$db->Fetch())
        {
            $data[]=array("id"=>$buf["id"],"Name"=>$buf["Name"],"FullName"=>$buf["FullName"],
                "City"=>$buf["City"],"Address"=>$buf["Address"],"Phone"=>$buf["Phone"],"IIN"=>$buf["IIN"]);
        }
        $db->StopFetch();
        if($data==null)
            die("{\"data\": []}");

        die("{\"data\": ".json_encode($data)."}");
        break;

    case "get_list":
        Mvc::View(basename(__FILE__,".php"),"list");
        break;

    case "GetIdByName":
        $name = $_REQUEST["name"];
        $r = $db->QueryOne("select id from Provider where Name='{$name}' ");
        die($r["id"]);
        break;

    case "Create":
        $name = $_REQUEST["name"];
        $fullname = $_REQUEST["fullname"];
        $city = $_REQUEST["city"];
        $address = $_REQUEST["address"];
        $phone = $_REQUEST["phone"];
        $iin = $_REQUEST["iin"];
        $guid = UUID::v4();

        $log->Write(basename(__FILE__,".php"),"Создание постащика".$name);

        $sql = " INSERT INTO `Provider` (`id`,  `Name`,  `FullName`,  `City`,  `Address`,  `Phone`, `IIN`)".
            " VALUE ('{$guid}','{$name}','{$fullname}','{$city}','{$address}','{$phone}','$iin');";

        if(!$db->Exec($sql))
        {
            die("Error add user!");
        }

        die("ok");
        break;

    case "Edit":
        $manufid = $_REQUEST["manufid"];
        $name = $_REQUEST["name"];
        $fullname = $_REQUEST["fullname"];
        $city = $_REQUEST["city"];
        $address = $_REQUEST["address"];
        $phone = $_REQUEST["phone"];
        $iin = $_REQUEST["iin"];

        $log->Write(basename(__FILE__,".php"),"Изменение постащика".$name);

        $sql = " update `Provider` set `Name`='{$name}',`FullName`='{$fullname}',".
            "`City`='{$city}',`Address`='{$address}' ,`Phone`='{$phone}',`IIN`='{$iin}'  where `id`='{$manufid}'";

        if(!$db->Exec($sql))
        {
            die("Error update user!");
        }

        die("ok");
        break;

    case "Delete":
        $manufid = $_REQUEST["manufid"];
        $r = $db->QueryOne("select Name from Provider where id='{$manufid}' ");
        $log->Write(basename(__FILE__,".php"),"Удаление постащика".$r["Name"]);

        $sql = " delete from `Provider` where `id`='{$manufid}'";

        if(!$db->Exec($sql))
        {
            die("Error delete user!");
        }

        die("ok");
        break;


    default: echo "Action not found"; break;
}



<?php

// город, Адрес, контактный лелефон, фио

switch($action)
{
    // ACTION =========================
    case "index":
        Mvc::View(basename(__FILE__,".php"));
        break;

    case "get_list":
        $db->Query("select id, Name,FullName,City,Address,Phone from Manufacturer order by Name desc");
        while($buf=$db->Fetch())
        {
            $data[]=array("id"=>$buf["id"],"Name"=>$buf["Name"],"FullName"=>$buf["FullName"],
                "City"=>$buf["City"],"Address"=>$buf["Address"],"Phone"=>$buf["Phone"]);
        }
        $db->StopFetch();
        echo "{\"data\": ".json_encode($data)."}";
        break;

    case "get_upload_list":

        break;


    default: echo "Action not found"; break;
}



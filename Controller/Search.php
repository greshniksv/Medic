<?php

switch($action)
{
    // ACTION =========================
    case "index":
        Mvc::View(basename(__FILE__,".php"));
        break;

    case "get_list":
        Mvc::View(basename(__FILE__,".php"),"list");
        break;

    case "get_list_data":
        $db->Query("select `id`,`Number`,`NumberProvider`,Name,FullName,`BasicCharacteristics`,`Price`,`Rest`, ".
            " (select Name from Provider where id=`ProviderId`) as ProviderId  ".
            " from `Products` order by Name ");
        while($buf=$db->Fetch())
        {
            $data[]=array("id"=>$buf["id"],"Number"=>$buf["Number"],"NumberProvider"=>$buf["NumberProvider"],
                "Name"=>$buf["Name"],"FullName"=>$buf["FullName"],"BasicCharacteristics"=>$buf["BasicCharacteristics"],
                "Price"=>$buf["Price"],"Rest"=>$buf["Rest"],"ProviderId"=>$buf["ProviderId"]);
        }
        $db->StopFetch();

        die("{\"data\": ".json_encode($data)."}");
        break;

    default: echo "Controller not found"; break;
}



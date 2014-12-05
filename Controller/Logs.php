<?php

switch($action)
{
    // ACTION =========================
    case "index":
        Mvc::View(basename(__FILE__,".php"));
        break;

    case "get_log_list":
        Mvc::View(basename(__FILE__,".php"),"list");
        break;

    case "get_log_list_data":
        $db->Query("select id, (select concat(FirstName,LastName) from `Users` u where u.id=l.UserId ) as user ".
            ",DateTime,Head,Message from `Logs` l order by DateTime desc");
        while($buf=$db->Fetch())
        {
            $data[]=array("id"=>$buf["id"],"user"=>$buf["user"],"DateTime"=>$buf["DateTime"],
                "Head"=>$buf["Head"],"Message"=>$buf["Message"]);
        }
        $db->StopFetch();

        if($data==null)
        {
            die("{\"data\": []}");
        }

        die("{\"data\": ".json_encode($data)."}");
        break;



    default: echo "Controller not found"; break;
}



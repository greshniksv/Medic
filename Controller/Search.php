<?php

switch($action)
{
    // ACTION =========================
    case "index":
        Mvc::View(basename(__FILE__,".php"));
        break;

    case "get_list":

        $data = array("search"=>@$_REQUEST["search"],"fname"=>@$_REQUEST["fname"],
            "provider"=>@$_REQUEST["provider"],"price"=>@$_REQUEST["price"],"rest"=>@$_REQUEST["rest"]);

        Mvc::View(basename(__FILE__,".php"),"list",$data);
        break;

    case "get_list_data":
        $search = @$_REQUEST["search"];
        $fname = @$_REQUEST["fname"];
        $provider = @$_REQUEST["provider"];
        $price = @$_REQUEST["price"];
        $rest = @$_REQUEST["rest"];

        $db->Query("select `id`,`Number`,`NumberProvider`,Name,FullName,`BasicCharacteristics`,`Price`,`Rest`, ".
            " (select Name from Provider where id=`ProviderId`) as ProviderId  ".
            " from `Products` ".
            " where id in (select ProductId from `ProductsSearch` where SearchString like '%{$search}%') ".
            (strlen($fname)>0?" and FullName like '%{$fname}%' ":"").
            (strlen($price)>0?" and Price like '%{$price}%' ":"").
            (strlen($provider)>0?" and ProviderId ='{$provider}' ":"").
            (strlen($rest)>0?" and Rest like '%{$rest}%' ":"").
            " order by Name ");
        while($buf=$db->Fetch())
        {
            $data[]=array("id"=>$buf["id"],"Number"=>$buf["Number"],"NumberProvider"=>$buf["NumberProvider"],
                "Name"=>$buf["Name"],"FullName"=>$buf["FullName"],"BasicCharacteristics"=>$buf["BasicCharacteristics"],
                "Price"=>$buf["Price"],"Rest"=>$buf["Rest"],"ProviderId"=>$buf["ProviderId"]);
        }
        $db->StopFetch();

        if(@$data==null)
            $data[]=array("id"=>"","Number"=>"","NumberProvider"=>"","Name"=>"","FullName"=>"","BasicCharacteristics"=>"",
                "Price"=>"","Rest"=>"","ProviderId"=>"");

        die("{\"data\": ".json_encode($data)."}");
        break;

    default: echo "Controller not found"; break;
}



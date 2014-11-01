<?php

switch($action)
{
    // ACTION =========================
    case "index":
        Mvc::View(basename(__FILE__,".php"));
        break;

    case "Create":
        $prov_code = @$_REQUEST["prov_code"];
        $pname = @$_REQUEST["pname"];
        $tpname = @$_REQUEST["tpname"];
        $prop = @$_REQUEST["prop"];
        $price = @$_REQUEST["price"];
        $rest = @$_REQUEST["rest"];
        $prov = @$_REQUEST["prov"];
        $dateExt = new DateTime;
        $cur_date = $dateExt->format("Y-m-d H:i:s");
        $guid = UUID::v4();

        $log->Write(basename(__FILE__,".php"),"Добавлен новый товар: номер $prov_code ");

        // create row for search
        $search_sql = "insert into `ProductsSearch` (ProductId,SearchString) values ('$guid','".$prov_code." ".$pname." ".$tpname." ".$prop." ".$price." ".$rest." ".$prov."')";

        $sql = "INSERT INTO `Products`(`id`,`NumberProvider`,`Name`,`FullName`,`BasicCharacteristics`,`ProviderId`," .
            "`Price`,`Rest`,Updated) VALUE ('$guid','$prov_code','$pname','$tpname','$prop','$prov','$price','$rest','$cur_date');";

        if(!$db->Exec($search_sql))
            die("Ошибка добавления товара!");

        if(!$db->Exec($sql))
            die("Ошибка добавления товара!");

        die("ok");

        break;

    case "Edit":

        $productId = @$_REQUEST["id"];
        $prov_code = @$_REQUEST["prov_code"];
        $pname = @$_REQUEST["pname"];
        $tpname = @$_REQUEST["tpname"];
        $prop = @$_REQUEST["prop"];
        $price = @$_REQUEST["price"];
        $rest = @$_REQUEST["rest"];
        $prov = @$_REQUEST["prov"];
        $dateExt = new DateTime;
        $cur_date = $dateExt->format("Y-m-d H:i:s");
        $guid = UUID::v4();

        $log->Write(basename(__FILE__,".php"),"Изменен товар: номер $prov_code ");

        // create row for search
        $search_sql = "update `ProductsSearch` set SearchString='".$prov_code." ".$pname." ".$tpname." ".$prop." ".$price." ".$rest." ".$prov."' where ProductId='$productId' ";

        $sql = "update Products set `NumberProvider`='$prov_code',`Name`='$pname',`FullName`='$tpname',".
            "`BasicCharacteristics`='$prop',`ProviderId`='$prov'," .
            "`Price`='$price',`Rest`='$rest',Updated = '$cur_date' where id = '$productId'";

        if(!$db->Exec($search_sql))
            die("Ошибка изменения товара!");

        if(!$db->Exec($sql))
            die("Ошибка изменения товара!");

        die("ok");

        break;

    case "Delete":

        $productId = @$_REQUEST["id"];

        $num = $db->QueryOne("select NumberProvider from Products where id = '$productId' ");

        $log->Write(basename(__FILE__,".php"),"Удален товар: номер $num ");

        $sql="delete from ProductsSearch where ProductId='$productId'";

        if(!$db->Exec($sql))
            die("Ошибка удаления товара!");

        $sql="delete from Products where id='$productId'";

        if(!$db->Exec($sql))
            die("Ошибка удаления товара!");

        die("ok");

        break;

    case "get_list":

        $data = array("search"=>@$_REQUEST["search"],"provider"=>@$_REQUEST["provider"]);

        Mvc::View(basename(__FILE__,".php"),"list",$data);
        break;

    case "get_list_data":
        $search = strtolower(@$_REQUEST["search"]);
        $provider = @$_REQUEST["provider"];
        if($provider=="0")$provider="";
        $search_string = "SearchString like '%{$search}%'";

        $s_item = explode(" ", $search);
        if (count($s_item) > 1) {
            $search_string="";
            $comb = Recombination::GetCombination(count($s_item));
            foreach ($comb as $c) {
                if (strlen($search_string) > 1) $search_string .= " or ";
                $search_string .= " SearchString like '%";
                foreach ($c as $i) {
                    $search_string .= $s_item[$i] . "%";
                }
                $search_string .= "'";
            }
        }

        //die($search_string);


        $sql = "select `id`,`Number`,`NumberProvider`,Name,FullName,`BasicCharacteristics`,`Price`,`Rest`, ".
            " (select Name from Provider where id=`ProviderId`) as Provider, ProviderId  ".
            " from `Products` ".
            " where id in (select ProductId from `ProductsSearch` where {$search_string} ) ". //SearchString like '%{$search}%'
            (strlen($provider)>0?" and ProviderId ='{$provider}' ":"").
            " order by Name LIMIT 1000";

        $db->Query($sql);
        while($buf=$db->Fetch())
        {
            $data[]=array("id"=>$buf["id"],"Number"=>$buf["Number"],"NumberProvider"=>$buf["NumberProvider"],
                "Name"=>$buf["Name"],"FullName"=>$buf["FullName"],"BasicCharacteristics"=>$buf["BasicCharacteristics"],
                "Price"=>$buf["Price"],"Rest"=>$buf["Rest"],"Provider"=>$buf["Provider"],"ProviderId"=>$buf["ProviderId"]);
        }
        $db->StopFetch();

        if(@$data==null)
            $data[]=array("id"=>"","Number"=>"","NumberProvider"=>"","Name"=>"","FullName"=>"","BasicCharacteristics"=>"",
                "Price"=>"","Rest"=>"","ProviderId"=>"","Provider"=>"");

        die("{\"data\": ".json_encode($data)."}");
        break;

    default: echo "Action not found"; break;
}

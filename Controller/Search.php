<?php
switch($action)
{
    // ACTION =========================
    case "index":
        Mvc::View(basename(__FILE__,".php"));
        break;

    case "download":
        $ids = @$_REQUEST["ids"];

        $id_list = explode(";",$ids);
        $id_qlist="";
        for($i=0;$i<count($id_list);$i++)
        {
            if(strlen($id_qlist)>2) $id_qlist.=",";
            $id_qlist.="'".$id_list[$i]."'";
        }

        // Extract provider info
        $sqlp = "select id, Name,FullName,City,Address,Phone,IIN from Provider order by Name desc";
        $db->Query($sqlp);
        while($buf=$db->Fetch())
        {
            $providers[]=array("id"=>$buf["id"],"Info"=>$buf["Name"].";".$buf["FullName"].";".$buf["City"].";".
                $buf["Address"].";".$buf["Phone"].";".$buf["IIN"]);

            //echo iconv("UTF-8", "windows-1251//IGNORE", $buffer);
        }
        $db->StopFetch();

        $sql = "select `Number`,`NumberProvider`,Name,FullName,`BasicCharacteristics`,`Unit`,`Price`,`Rest`,ProviderId ".
            //" (select Name from Provider where id=`ProviderId`) as ProviderId  ".
            " from `Products` where id in ($id_qlist) ";

        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="export.csv"'); //<<< Note the " " surrounding the file name
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        $buffer = "Код товара в базе поставщика;Наименование товара;Торговое наименование;Основные характеристики товара".
            ";Единица измерения;Цена в рублях;Остаток;Наименование постащика;ФИО;Город;Адрес;Телефон;ИИН\n";
        echo iconv("UTF-8", "windows-1251//IGNORE", $buffer);

        $db->Query($sql);
        while($buf=$db->Fetch())
        {
            $pinfo ="";

            for($i=0;$i<count($providers);$i++)
            {
                if($providers[$i]["id"]==$buf["ProviderId"])
                {
                    $pinfo=$providers[$i]["Info"];
                    break;
                }
            }

            $buffer = $buf["NumberProvider"].";".$buf["Name"].";".$buf["FullName"].";".$buf["BasicCharacteristics"].";".
                $buf["Unit"].";".$buf["Price"].";".$buf["Rest"].";".$pinfo."\n";

            echo iconv("UTF-8", "windows-1251//IGNORE", $buffer);
        }
        $db->StopFetch();

        break;

    case "get_list":

        $data = array("search"=>@$_REQUEST["search"],"fname"=>@$_REQUEST["fname"],
            "provider"=>@$_REQUEST["provider"],"price1"=>@$_REQUEST["price1"],"rest"=>@$_REQUEST["rest"],
            "prop"=>@$_REQUEST["prop"],"pname"=>@$_REQUEST["pname"],"rest"=>@$_REQUEST["rest"],
            "code"=>@$_REQUEST["code"],"price2"=>@$_REQUEST["price2"]
        );

        Mvc::View(basename(__FILE__,".php"),"list",$data);
        break;

    case "get_list_data":
        $search = strtolower(@$_REQUEST["search"]);
        $fname = @$_REQUEST["fname"];
        $pname = @$_REQUEST["pname"];
        $prop = @$_REQUEST["prop"];
        $provider = @$_REQUEST["provider"];
        $price_from = @$_REQUEST["price1"];
        $price_to = @$_REQUEST["price2"];
        $rest = @$_REQUEST["rest"];
        $code = @$_REQUEST["code"];
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


        $sql = "select `id`,`Number`,`NumberProvider`,Name,FullName,`BasicCharacteristics`,`Unit`,`Price`,`Rest`, ".
            " (select Name from Provider where id=`ProviderId`) as Provider,ProviderId  ".
            " from `Products` ".
            " where id in (select ProductId from `ProductsSearch` where {$search_string} ) ". //SearchString like '%{$search}%'
            (strlen($fname)>0?" and FullName like '%{$fname}%' ":"").
            (strlen($price_from)>0?" and (Price >= '$price_from' and Price <= '$price_to' ) ":"").
            (strlen($provider)>0?" and ProviderId ='{$provider}' ":"").
            (strlen($rest)>0?" and Rest like '%{$rest}%' ":"").
            (strlen($prop)>0?" and BasicCharacteristics like '%{$prop}%' ":"").
            (strlen($pname)>0?" and Name like '%{$pname}%' ":"").
            (strlen($code)>0?" and NumberProvider like '%{$code}%' ":"").
            " order by Name LIMIT 1000";

        $db->Query($sql);
        while($buf=$db->Fetch())
        {
            $data[]=array("id"=>$buf["id"],"Number"=>$buf["Number"],"NumberProvider"=>$buf["NumberProvider"],
                "Name"=>$buf["Name"],"FullName"=>$buf["FullName"],"BasicCharacteristics"=>$buf["BasicCharacteristics"],
                "Unit"=>$buf["Unit"],"Price"=>$buf["Price"],"Rest"=>$buf["Rest"],"Provider"=>$buf["Provider"],"ProviderId"=>$buf["ProviderId"]);
        }
        $db->StopFetch();

        if(@$data==null)
        {
            die("{\"data\": []}");
        }
        //    $data[]=array("id"=>"","Number"=>"","NumberProvider"=>"","Name"=>"","FullName"=>"","BasicCharacteristics"=>"",
        //       "Unit"=>"","Price"=>"","Rest"=>"","ProviderId"=>"");

        die("{\"data\": ".json_encode($data)."}");
        break;

    default: echo "Controller not found"; break;
}
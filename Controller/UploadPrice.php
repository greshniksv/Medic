<?php

switch($action)
{
    // ACTION =========================
    case "index":
        Mvc::View(basename(__FILE__,".php"));
        break;

    case "get_status":
        $id = $_REQUEST["id"];
        $rez = $db->QueryOne("select Status from Uploads where id='{$id}'");
        $data = array('status' => $rez["Status"]);
        die(json_encode($data));
        break;

    case "upload":

        if(count($_FILES)==1)
        {
            $files = array();
            $upl_file = $_FILES[0]['tmp_name'];
            $upl_name = $_FILES[0]['name'];
            $manuf = $_REQUEST["manuf"];
            $orig = $_REQUEST["orig"];

            $log->Write(basename(__FILE__,".php"),"Загрузка прайса:".$upl_name);

            $myfile = fopen($upl_file, "r") or die("Unable to open file!");
            // Output one line until end-of-file
            //while(!feof($myfile)) {
            //    echo fgets($myfile) . "<br>";
            //}
            fclose($myfile);

            $guid = UUID::v4();
            $date = date("Y-m-d H:i:s");
            $user = Session::GetUserId($cookie);

            $db->Exec("insert into Uploads (id, FileName,DateTime,UserId,ProviderId,Status) values ".
                " ('{$guid}','{$upl_name}','{$date}','{$user}','{$manuf}','Готово') ");



            if($orig=="1")
            {
                $new_file = getcwd() . "/Files/InitialPrice/".$upl_name;
                $dnew_file = "Files/InitialPrice/".$upl_name;
                @unlink($new_file);
                $db->Exec("update `Provider` set InitialPrice='{$dnew_file}' where id='{$manuf}' ");

                if (!move_uploaded_file($upl_file, $new_file)) {
                    return "Невозможно переместить файл";
                }
                $data = array('success' => 'Form was submitted');
                die(json_encode($data));
            }

            // Start price processing
            $worker = new ProcessPriceWorker($manuf,$upl_file,$guid);
            $ret = $worker->run();
            /*if($ret!="ok")
            {
                $data = array('error' => $ret);
                die(json_encode($data));
            }

            $data = array('success' => 'Form was submitted');*/
            die($ret);
        }

        $data = array('error' => 'File not found!');
        die(json_encode($data));

        break;


    case "process":
        $id = $_REQUEST["id"];
        $file = $_REQUEST["file"];
        $procid = $_REQUEST["procid"];

        $worker = new ProcessPriceWorker();
        $ret = $worker->Process($id,$file,$procid);

        die($ret);
        break;


    case "get_upload_list":
        Mvc::View(basename(__FILE__,".php"),"list");
        break;

    case "get_upload_list_data":
        $db->Query("select id, FileName,DateTime,(select concat(FirstName,' ',LastName) from Users where id=UserId) as UserId,".
            "(select Name from `Provider` where id=ProviderId)as ProviderId,Status from Uploads order by DateTime desc");
        while($buf=$db->Fetch())
        {
            $data[]=array("id"=>$buf["id"],"FileName"=>$buf["FileName"],"DateTime"=>$buf["DateTime"],
                "UserId"=>$buf["UserId"],"ProviderId"=>$buf["ProviderId"],"Status"=>$buf["Status"]);
        }
        $db->StopFetch();

        if($data==null)
            die("{\"data\": []}");

        die("{\"data\": ".json_encode($data)."}");
        break;

    case "clear_provider":
        $manuf = $_REQUEST["manuf"];
        if($db->Exec("delete from Products where ProviderId='{$manuf}'; "))
        {
            die("Продукты постащика удалены!");
        }
        else
            die("Ошибка удаления товаров!");

        break;


    default: echo "Action not found"; break;
}



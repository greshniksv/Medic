<?php

switch($action)
{
    // ACTION =========================
    case "index":
        Mvc::View(basename(__FILE__,".php"));
        break;

    case "upload":

        if(count($_FILES)==1)
        {
            $files = array();
            $upl_file = $_FILES[0]['tmp_name'];
            $upl_name = $_FILES[0]['name'];
            $manuf = $_REQUEST["manuf"];

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

            $db->Query("insert into Uploads (id, FileName,DateTime,UserId,ManufacturerId,Status) values ".
                " ('{$guid}','{$upl_name}','{$date}','{$user}','{$manuf}','Загружено') ");

            // Start price processing
            $worker = new ProcessPriceWorker($guid,$upl_file);
            $worker->run();

            $data = array('success' => 'Form was submitted');
            die(json_encode($data));
        }

        $data = array('success' => 'File not found!');
        die(json_encode($data));

        break;


    case "get_upload_list":
        Mvc::View(basename(__FILE__,".php"),"uploaded_list");
        break;

    case "get_upload_list_data":
        $db->Query("select id, FileName,DateTime,(select concat(FirstName,' ',LastName) from Users where id=UserId) as UserId,".
            "(select Name from `Manufacturer` where id=ManufacturerId)as ManufacturerId,Status from Uploads order by DateTime desc");
        while($buf=$db->Fetch())
        {
            $data[]=array("id"=>$buf["id"],"FileName"=>$buf["FileName"],"DateTime"=>$buf["DateTime"],
                "UserId"=>$buf["UserId"],"ManufacturerId"=>$buf["ManufacturerId"],"Status"=>$buf["Status"]);
        }
        $db->StopFetch();

        echo "{\"data\": ".json_encode($data)."}";
        //print_r($data);
        die();
        break;


    default: echo "Action not found"; break;
}



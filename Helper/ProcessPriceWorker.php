<?php

class ProcessPriceWorker
{
    public function __construct($id="", $file_name="", $process_id="")
    {
        $this->id = $id;
        $this->file_name = $file_name;
        $this->process_id = $process_id;
    }


    public function run()
    {
        global $CONFIG;

        $handle = @fopen($this->file_name, "r");
        if (!$handle) {
            die(json_encode(array('error' => 'Error open file!')));
        }

        if (($buffer = fgets($handle, 4096)) !== false) {
            $c = substr_count($buffer, ';');
            if (substr_count($buffer, ';') != 6) {
                fclose($handle);
                return json_encode(array('error' => "Формат файла не верен:" . $c));
            }
        } else {
            return json_encode(array('error' => "Невозможно открыть файл"));
        }
        fclose($handle);

        $new_file = getcwd() . "/Files/" . $guid = $this->process_id . "";
        if (!move_uploaded_file($this->file_name, $new_file)) {
            return json_encode(array('error' => "Невозможно переместить файл"));
        }
        //exec("iconv -f windows-1251 -t utf-8 {$new_file} -o {$new_file}.csv");
        //unlink($new_file);



        $outputfile = $CONFIG["PRICE_WORKER_LOG"];//"/var/www/Medic/ProcessPriceWorker.log";
        $pidfile = "pid.txt";
        //exec(sprintf("%s >> %s 2>&1 & echo $! >> %s", $PHP . " " . __FILE__ . " {$this->id} {$new_file} {$this->process_id}", $outputfile, $pidfile)); //.csv
        $data=array("id"=>$this->id,"file"=>$new_file,"procid"=>$this->process_id);
        return "{\"data\": ".json_encode($data)."}";
    }

    public function Process($id,$new_file,$process_id)
    {
        global $CONFIG;
        $dateExt = new DateTime;
        $cur_date = $dateExt->format("Y-m-d H:i:s");
        $prov_data = "";
        $to_search = new ArrayObject();

        echo "\n\n------------------------------------------------------------------------------\n";
        echo "Start process: " . $cur_date . "\n";
        echo "Input params: \n";

        $db = new Database($CONFIG["DB_HOST"], $CONFIG["DB_NAME"], $CONFIG["DB_USER"], $CONFIG["DB_PASS"]);
        $db->Connect();

        $filesize = filesize($new_file);
        $handle = @fopen($new_file, "r");
        if (!$handle) {
            $db->Exec("update `Uploads` set Status='Ошибка' where id='" . $process_id . "' ;");
            die("Error open file!");
        }

        echo "\nInput file size:" . $filesize."\n";

        if (($buffer = fgets($handle, 4096)) !== false) {
            if (substr_count($buffer, ';') != 6) {
                fclose($handle);
                $db->Exec("update `Uploads` set Status='Ошибка' where id='" . $process_id . "' ;");
                die("Incorrect file format");
            }
        } else {
            $db->Exec("update `Uploads` set Status='Ошибка' where id='" . $process_id . "' ;");
            die("Can't open file");
        }


        try {

            echo "Structure of file: OK\n";
            //echo $buffer;

            $db->Exec("update `Uploads` set Status='Подготовка 10%' where id='" . $process_id . "' ;");
            $db->Exec("delete from `ProductsSearch` where ProductId in (select id from `Products` where ProviderId='" . $id . "');");
            $db->Exec("update `Uploads` set Status='Подготовка 40%' where id='" . $process_id . "' ;");
            $db->Exec("delete from `Products` where ProviderId='" . $id . "' ;");
            $db->Exec("update `Uploads` set Status='Подготовка 70%' where id='" . $process_id . "' ;");
            //$db->Exec("delete from `ProductsSearch` where ProductId not in (select id from `Products`);");

            echo "Clean before process\n";


            $dbh = new PDO("mysql:host=" . $CONFIG["DB_HOST"] . ";dbname=" . $CONFIG["DB_NAME"], $CONFIG["DB_USER"], $CONFIG["DB_PASS"]);

            $sql = "SELECT Name,FullName,City,Address,Phone FROM Provider where id='{$id}' ";
            foreach ($dbh->query($sql) as $row) {
                $prov_data = $row['Name'] . $row['FullName'] . $row['City'] . $row['Address'] . $row['Phone'];
            }

            echo "Prepare to process\n";
            $dbh->beginTransaction();

            $sql = "INSERT INTO `Products`(`id`,`NumberProvider`,`Name`,`FullName`,`BasicCharacteristics`,`ProviderId`," .
                "`Unit`,`Price`,`Rest`,Updated) VALUE (?,?,?,?,?,?,?,?,?,?);";

            $sth = $dbh->prepare($sql);

            $readed = 0;
            $progress = 0;
            while (($buffer = fgets($handle, 4096)) !== false) {
                $readed += strlen($buffer);

                if ($progress != intval(($readed * 100 / $filesize))) {
                    $progress = intval(($readed * 100 / $filesize));
                    echo "Processing " . $progress . "\n";

                    $db->Exec("update `Uploads` set Status='Обработка " . $progress . "%' where id='" . $process_id . "' ;");
                }

                $buffer = iconv("windows-1251", "UTF-8//IGNORE", $buffer);
                //$html_utf8 = mb_convert_encoding($html, "utf-8", "windows-1251");
                //$buffer = mb_strtolower($buffer, 'UTF-8');
                //echo $buffer."\n";
                $elem = explode(";", $buffer);
                $guid = UUID::v4();


                $ss = $elem[0] . $elem[1] . $elem[2] . $elem[3] . $id . $elem[4] . $elem[5]. $elem[6] . $prov_data;
                $to_search[count($to_search)] = array("id" => $guid, "data" => mb_strtolower($ss, 'UTF-8'));

                $sth->execute(array(
                    $guid, $elem[0], $elem[1], $elem[2], $elem[3], $id,$elem[4], $elem[5], $elem[6], $cur_date
                ));
            }

            echo "Finished processing\n";

            $sql = "insert into `ProductsSearch` (ProductId,SearchString) values (?,?);";

            echo "Count of item:" . count($to_search) . "\n";

            $progress = 0;
            $col = 0;
            $sth = $dbh->prepare($sql);
            foreach ($to_search as $i) {
                $col++;
                if ($progress != intval($col * 100 / count($to_search))) {
                    $progress = intval($col * 100 / count($to_search));
                    echo "Processing " . $progress . "\n";
                    $db->Exec("update `Uploads` set Status='Постобработка " . $progress . "%' where id='" . $process_id . "' ;");
                }

                $sth->execute(array($i["id"], $i["data"]));
            }
            echo "Finished optimization\n";

            $dbh->commit();

        } catch (PDOException $e) {
            $dbh->rollBack();
            $db->Exec("update `Uploads` set Status='Ошибка' where id='" . $process_id . "' ;");
            die("Error!: " . $e->getMessage() . "<br/>");
        }

        if (!feof($handle)) {
            $db->Exec("update `Uploads` set Status='Ошибка' where id='" . $process_id . "' ;");
            echo "Error: unexpected fgets() fail\n";
        }

        $db->Exec("update `Uploads` set Status='Готово' where id='" . $process_id . "' ;");
        fclose($handle);
        sleep(1);

        if($CONFIG["DEBUG"]!=1) unlink($new_file);
    }


}




if (!isset($PHP)) {
    // Do process file

    require_once 'Helper/UUID.php';
    require_once 'config.php';
    require_once 'Database.php';
    require_once 'FixSearch.php';


}

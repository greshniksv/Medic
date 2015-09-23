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
            $charCount = substr_count($buffer, ';');
            if ($charCount != 7) {
                fclose($handle);
                return json_encode(array('error' => "Формат файла не верен:" . $charCount));
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
        return "{\"data\": ".@json_encode($data)."}";
    }

	public function WriteLog($file, $data)
	{
		echo $data;
		for($i=0; $i<30; $i++)
		{
			try
			{
				$result = file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
				if($result !== false){
					break;
				}
			}
			catch(Exception $e) {}
		}
	}
	
    public function Process($id,$new_file,$process_id)
    {
        global $CONFIG;
		$outputfile = $CONFIG["PRICE_WORKER_LOG"];
        $dateExt = new DateTime;
        $cur_date = $dateExt->format("Y-m-d H:i:s");
        $prov_data = "";
        $to_search = new ArrayObject();

        $this->WriteLog($outputfile,"\n\n------------------------------------------------------------------------------\n");
        $this->WriteLog($outputfile, "Start process: " . $cur_date . "\n");
        $this->WriteLog($outputfile, "Input params: \n");
		$this->WriteLog($outputfile, "* id: ".$id."\n* file: ".$new_file."\n* processId: ".$process_id." \n-- \n");

        $db = new Database($CONFIG["DB_HOST"], $CONFIG["DB_NAME"], $CONFIG["DB_USER"], $CONFIG["DB_PASS"]);
        $db->Connect();

        $filesize = filesize($new_file);
        $handle = @fopen($new_file, "r");
        if (!$handle) {
            $db->Exec("update `Uploads` set Status='Ошибка' where id='" . $process_id . "' ;");
			$this->WriteLog($outputfile, "Error open file! \n");
            die("Error open file!");
        }

        $this->WriteLog($outputfile, "\nInput file size:" . $filesize."\n");

        if (($buffer = fgets($handle, 4096)) !== false) {
			$columnsCount = substr_count($buffer, ';');
            if ($columnsCount != 7) {
                fclose($handle);
                $db->Exec("update `Uploads` set Status='Ошибка' where id='" . $process_id . "' ;");
				$this->WriteLog($outputfile, "Incorrect file format. Columns count must be: 7. Actual: ".$columnsCount." \n");
                die("Incorrect file format");
            }
        } else {
            $db->Exec("update `Uploads` set Status='Ошибка' where id='" . $process_id . "' ;");
			$this->WriteLog($outputfile, "Can't open file or file is empty ! \n");
            die("Can't open file");
        }


        try {

            $this->WriteLog($outputfile, "Structure of file: OK\n");

            $db->Exec("update `Uploads` set Status='Подготовка 10%' where id='" . $process_id . "' ;");
            $db->Exec("delete from `ProductsSearch` where ProductId in (select id from `Products` where ProviderId='" . $id . "');");
            $db->Exec("update `Uploads` set Status='Подготовка 40%' where id='" . $process_id . "' ;");
            $db->Exec("delete from `Products` where ProviderId='" . $id . "' ;");
            $db->Exec("update `Uploads` set Status='Подготовка 70%' where id='" . $process_id . "' ;");
            //$db->Exec("delete from `ProductsSearch` where ProductId not in (select id from `Products`);");

            $this->WriteLog($outputfile, "Clean before process\n");


            $dbh = new PDO("mysql:host=" . $CONFIG["DB_HOST"] . ";dbname=" . $CONFIG["DB_NAME"], $CONFIG["DB_USER"], $CONFIG["DB_PASS"]);
			$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

            $sql = "SELECT Name,FullName,City,Address,Phone FROM Provider where id='{$id}' ";
            foreach ($dbh->query($sql) as $row) {
                $prov_data = $row['Name'] . $row['FullName'] . $row['City'] . $row['Address'] . $row['Phone'];
            }

            $this->WriteLog($outputfile, "\nPrepare to process\n");
            $dbh->beginTransaction();

            $sql = "INSERT INTO `Products`(`id`,`Number`,`NumberProvider`,`Name`,`FullName`,`BasicCharacteristics`,`ProviderId`," .
                "`Unit`,`Price`,`Rest`,`Updated`) VALUE (?,?,?,?,?,?,?,?,?,?,?);";

            $sth = $dbh->prepare($sql);
			
			if (!$sth) {
				$this->WriteLog($outputfile, "PDO::errorInfo():\n" . print_r($dbh->errorInfo(), true));
			}
			
            $readed = 0;
            $progress = 0;
            while (($buffer = fgets($handle, 4096)) !== false) {
                $readed += strlen($buffer);

                if ($progress != intval(($readed * 100 / $filesize))) {
                    $progress = intval(($readed * 100 / $filesize));
                    $this->WriteLog($outputfile, "Updating product " . $progress . "\n");

                    $db->Exec("update `Uploads` set Status='Обработка " . $progress . "%' where id='" . $process_id . "' ;");
                }

                $buffer = iconv("windows-1251", "UTF-8//IGNORE", $buffer);
                $elem = explode(";", $buffer);
                $guid = UUID::v4();

				if(!(empty($elem[0]) || empty($elem[1]) || empty($elem[2])))
				{
					$ss = $elem[0] . $elem[1] . $elem[2] . $elem[3] . $elem[4] . $id . $elem[5]. $elem[6] . $elem[7] . $prov_data;
					$to_search[count($to_search)] = array("id" => $guid, "data" => mb_strtolower($ss, 'UTF-8'));

					$sth->execute(array(
						$guid, $elem[0], $elem[1], $elem[2], $elem[3], $elem[4], $id, $elem[5], str_replace(",", ".", $elem[6]), $elem[7], $cur_date
					));
				}
            }

            $this->WriteLog($outputfile, "Finished processing\n\n");

            $sql = "insert into `ProductsSearch` (ProductId,SearchString) values (?,?);";

            $this->WriteLog($outputfile, "Count of item:" . count($to_search) . "\n\n");

            $progress = 0;
            $col = 0;
            $sth = $dbh->prepare($sql);
            foreach ($to_search as $i) {
                $col++;
                if ($progress != intval($col * 100 / count($to_search))) {
                    $progress = intval($col * 100 / count($to_search));
                    $this->WriteLog($outputfile, "Optimization search " . $progress . "\n");
                    $db->Exec("update `Uploads` set Status='Постобработка " . $progress . "%' where id='" . $process_id . "' ;");
                }

                $sth->execute(array($i["id"], $i["data"]));
            }
            $this->WriteLog($outputfile, "Optimization finished\n");

            $dbh->commit();

        } catch (PDOException $e) {
            $dbh->rollBack();
            $db->Exec("update `Uploads` set Status='Ошибка' where id='" . $process_id . "' ;");
			$this->WriteLog($outputfile, "Exception while update product: ".$e->getMessage()."\n");
            die("Error!: " . $e->getMessage() . "<br/>");
        }
		catch (Exception $e) {
			$this->WriteLog($outputfile, "Exception while update product: ".$e->getMessage()."\n");
		}

        if (!feof($handle)) {
            $db->Exec("update `Uploads` set Status='Ошибка' where id='" . $process_id . "' ;");
            $this->WriteLog($outputfile, "Error: unexpected fgets() fail\n");
        }

        $db->Exec("update `Uploads` set Status='Готово' where id='" . $process_id . "' ;");
        fclose($handle);
        sleep(1);

        if($CONFIG["DEBUG"]!=1){ 
			unlink($new_file); 
			$this->WriteLog($outputfile, "Remove uploaded file\n");
		}else{
			$this->WriteLog($outputfile, "Uploaded file will not be deleted, because debug flag is turned on.\n");
		}
    }


}




if (!isset($PHP)) {
    // Do process file

    require_once 'Helper/UUID.php';
    require_once 'config.php';
    require_once 'Database.php';
    require_once 'FixSearch.php';


}

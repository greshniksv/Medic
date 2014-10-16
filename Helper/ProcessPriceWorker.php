<?php

class ProcessPriceWorker
{
    public function __construct($id, $file_name)
    {
        $this->id = $id;
        $this->file_name = $file_name;
    }

    public function run()
    {
        global $PHP;

        $handle = @fopen($this->file_name, "r");
        if (!$handle) {
            die("Error open file!");
        }

        if (($buffer = fgets($handle, 4096)) !== false) {
            $c = substr_count($buffer, ';');
            if (substr_count($buffer, ';') != 6) {
                fclose($handle);
                return "Формат файла не верен:" . $c;
            }
        } else {
            return "Невозможно открыть файл";
        }
        fclose($handle);

        $new_file = getcwd() . "/Files/" . $guid = UUID::v4() . "";
        if (!move_uploaded_file($this->file_name, $new_file)) {
            return "Невозможно переместить файл";
        }
        exec("iconv -f windows-1251 -t utf-8 {$new_file} -o {$new_file}.csv");
        unlink($new_file);

        $outputfile = "/var/www/Medic/out.txt";
        $pidfile = "pid.txt";
        exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $PHP . " " . __FILE__ . " {$this->id} {$new_file}.csv ", $outputfile, $pidfile));
        return "ok";
    }
}

if (!isset($PHP)) {
    // Do process file

    require_once 'Helper/UUID.php';
    require_once 'config.php';

    $dateExt   = new DateTime;
    $cur_date = $dateExt->format("Y-m-d H:i:s");
    $prov_data="";

    $handle = @fopen($argv[2], "r");
    if (!$handle) {
        die("Error open file!");
    }

    if (($buffer = fgets($handle, 4096)) !== false) {
        if (substr_count($buffer, ';') != 6) {
            fclose($handle);
            die("Incorrect file format");
        }
    } else {
        die("Can't open file");
    }

    //echo $buffer;


    $dbh;
    try {
        $dbh = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);

        $chk_stor = "SELECT count(*) as col FROM mysql.proc p WHERE name = 'FixSearch'";

        $q = $dbh->query($chk_stor);
        $row =$q->fetch(PDO::FETCH_ASSOC);
        if($row['col']<1)
        {
            $fsearch = "
            CREATE procedure FixSearch()
            begin
                DECLARE done BOOLEAN DEFAULT FALSE;
                DECLARE _id VARCHAR(36);
                DECLARE cur  CURSOR FOR select id from `Products` where id not in ( select ProductId from `ProductsSearch`);
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done := TRUE;

                delete from `ProductsSearch` where ProductId not in (select id from `Products`);

                OPEN cur;

                testLoop: LOOP
                 FETCH cur INTO _id;
                 IF done THEN
                   LEAVE testLoop;
                 END IF;

                 insert into `ProductsSearch` (ProductId,SearchString) values (_id,
                 (select REPLACE(LOWER(concat(`NumberProvider`,p.`Name`,p.`FullName`,`BasicCharacteristics`,`Price`,`Rest`,pr.Name,pr.FullName,City,Address,Phone)),' ','')
                    from `Products` p,`Provider` pr where pr.id = p.`ProviderId` and p.id=_id));

                END LOOP testLoop;

              CLOSE cur;

            end ";

            $dbh->query($fsearch);
        }

        $dbh->beginTransaction();

        $sql = "INSERT INTO `Products`(`id`,`NumberProvider`,`Name`,`FullName`,`BasicCharacteristics`,`ProviderId`," .
            "`Price`,`Rest`,Updated) VALUE (UUID(),?,?,?,?,?,?,?,?);";

        $sth = $dbh->prepare($sql);

        while (($buffer = fgets($handle, 4096)) !== false) {
            //echo $buffer."\n";
            //$buffer = iconv("UTF-8", "CP1251//IGNORE", $buffer);
            //echo $buffer."\n";
            $elem = explode(";", $buffer);
            $guid = UUID::v4();

            $sth->execute(array(
                $elem[0], $elem[1], $elem[2], $elem[3], $argv[1], $elem[4], $elem[5],$cur_date
            ));
        }

        $dbh->commit();
        $dbh->query("call FixSearch()");

    } catch (PDOException $e) {
        $dbh->rollBack();
        die("Error!: " . $e->getMessage() . "<br/>");
    }


    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }

    fclose($handle);
    sleep(1);
    unlink($argv[2]);

    print_r($argv);
}

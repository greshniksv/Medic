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
        $dbh->beginTransaction();
        //$sth = $dbh->exec("DROP TABLE fruit");


        $sql = "INSERT INTO `Products`(`id`,`NumberProvider`,`Name`,`FullName`,`BasicCharacteristics`,`ProviderId`," .
            "`Price`,`Rest`) VALUE (?,?,?,?,?,?,?,?);";
        $sql2 = "INSERT INTO `ProductsSearch`(`ProductId`,`SearchString`) VALUE (?,?);";

        $sth = $dbh->prepare($sql);
        $sth2 = $dbh->prepare($sql2);

        while (($buffer = fgets($handle, 4096)) !== false) {
            //echo $buffer."\n";
            //$buffer = iconv("UTF-8", "CP1251//IGNORE", $buffer);
            //echo $buffer."\n";
            $elem = explode(";", $buffer);
            $guid = UUID::v4();

            $sth->execute(array(
                $guid, $elem[0], $elem[1], $elem[2], $elem[3], $argv[1], $elem[4], $elem[5]
            ));

            $fsearch = str_replace(array(" ", ";"), "", $buffer);

            $sth2->execute(array(
                $guid, $fsearch
            ));
        }

        $dbh->commit();


    } catch (PDOException $e) {
        $dbh->rollBack();
        die("Error!: " . $e->getMessage() . "<br/>");
    }


    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }

    fclose($handle);
    sleep(1);
    //unlink($argv[2]);

    print_r($argv);
}

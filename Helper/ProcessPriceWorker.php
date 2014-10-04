<?php

class ProcessPriceWorker
{
    public function __construct($id,$file_name){
        $this->id=$id;
        $this->file_name=$file_name;
    }

    public function run(){
        global $PHP;

        $outputfile="/var/www/Medic/out.txt";
        $pidfile="pid.txt";
        exec(sprintf("%s > %s 2>&1 & echo $! >> %s",$PHP." ".__FILE__." {$this->id} {$this->file_name} ", $outputfile, $pidfile));
    }
}

if(!isset($PHP))
{
   // Do process file

    require_once 'Helper/UUID.php';
    require_once 'config.php';


    /*require_once 'Helper/Database.php';
    $db = new Database($DB_HOST, $DB_NAME, $DB_USER, $DB_PASS);
    $db -> Connect();

    $db->Exec("INSERT INTO Products(id,Number,NumberProvider,Name,FullName,BasicCharacteristics,ProviderId,Price,Rest) VALUE ('cc9cce8c-1afb-4151-8612-d5b91589c3d8','0','0','0','0','0','ba8b733a-5154-4e7a-aeaa-f9d90258a238','9','9');");
*/

    $dbh;
    try {
        $dbh = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
        $dbh->beginTransaction();
        //$sth = $dbh->exec("DROP TABLE fruit");


        $sql = "INSERT INTO `Products`(`id`,`Number`,`NumberProvider`,`Name`,`FullName`,`BasicCharacteristics`,`ProviderId`,".
            "`Price`,`Rest`) VALUE (?,?,?,?,?,?,?,?,?);";
        $sql2 = "INSERT INTO `ProductsSearch`(`ProductId`,`SearchString`) VALUE (?,?);";

        $sth = $dbh->prepare($sql);
        $sth2 = $dbh->prepare($sql2);

        for($i=0;$i<50;$i++)
        {
            echo $i;
            sleep(1);
            $guid = UUID::v4();

            $sth->execute(array(
                $guid,'0','0','0','0','0',$argv[1],'9','9'.$i
            ));

            $sth2->execute(array(
                $guid,'0sdfgsdgdfgfdgdfgfdgdfgfdgfd'.$i
            ));
        }

        $dbh->commit();

    } catch (PDOException $e) {
        $dbh->rollBack();
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }


    print_r($argv);
}

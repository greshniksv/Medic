<?php

class ProcessPriceWorker
{
    public function __construct($id,$file_name){
        $this->id=$id;
        $this->file_name=$file_name;
    }

    public function run(){
        global $db,$PHP;

        $outputfile="/var/www/Medic/out.txt";
        $pidfile="pid.txt";
        exec(sprintf("%s > %s 2>&1 & echo $! >> %s",$PHP." ".__FILE__, $outputfile, $pidfile));

    }

}

if(!isset($PHP))
{
   // Do process file


}

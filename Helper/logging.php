<?php

class Logging
{
    var $db;
    var $session;

    function __construct($db,$session) {
        $this->db=$db;
        $this->$session=$session;
    }

    function Write($head, $message)
    {
        $guid = UUID::v4();
        $date = date("Y-m-d H:i:s");
        $this->db->Exec("INSERT INTO `Logs`(`id`,`SessionId`,`DateTime`,`Head`,`Message`)
          VALUE ('{$guid}','{$this->$session}','NOW()','{$head}','{$message}'); ");
    }


}

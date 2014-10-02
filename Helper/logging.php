<?php

class Logging
{
    var $db;
    var $session;

    function __construct($db,$session) {
        $this->db=$db;
        $this->session=$session;
    }

    function DeleteOld()
    {
        $r = $this->db->QueryOne("select count(*) as col from `Logs`");
        if($r["col"]>5000)
        {
            $this->db->Exec("DELETE FROM `Logs` ORDER BY DateTime ASC limit 1000");
        }
    }

    function Write($head, $message)
    {
        self::DeleteOld();

        $guid = UUID::v4();
        $date = date("Y-m-d H:i:s");
        $user = Session::GetUserId($this->session);
        $this->db->Exec("INSERT INTO `Logs`(`id`,`UserId`,`DateTime`,`Head`,`Message`)
          VALUE ('{$guid}','{$user}','{$date}','{$head}','{$message}'); ");
    }




}

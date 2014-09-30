<?php

class Session
{
    public static function IsActiveSession($sessoin){
        global $db;
        $d = $db->QueryOne("select count(*) as col from Session where id = '{$sessoin}' ");
        return $d["col"]>0;
    }

    public static function GetUserId($sessoin)
    {
        global $db;

        $d = $db->QueryOne("select UserId from Session where id = '{$sessoin}' ");
        return $d["UserId"];
    }

    public static function GenId($userid)
    {
        global $db;

        $guid = UUID::v4();
        $date = date("Y-m-d H:i:s");
        $dateExt   = new DateTime;
        $dateExt->modify( '+1 hour' );
        $def = $dateExt->format("Y-m-d H:i:s");

        $db->Exec("INSERT INTO `Session`(`id`,`UserId`,`CreateDate`,`ExpireDate`)
                      VALUE ('{$guid}','{$userid}','{$date}','{$def}'); ");

        return $guid;
    }

    public static function DeleteUnusedSession()
    {
        global $db;
        $dateExt   = new DateTime;
        $dateExt->modify( '+1 hour' );
        $def = $dateExt->format("Y-m-d H:i:s");

        $db->Exec("delete from `Session` where `ExpireDate`< CAST('{$def}' as datetime) ");
    }

}




<?php

class Session
{
    public static function GenPassword($login, $length = 10)
    {
        global $db;
        $characters = '0123456789'; //abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ
        $new_pass = '';
        for ($i = 0; $i < $length; $i++) {
            $new_pass .= $characters[rand(0, strlen($characters) - 1)];
        }

        $d = $db->QueryOne("select id from Users where Login = '{$login}' ");
        $userid = $d["id"];

        $guid = UUID::v4();
        $dateExt   = new DateTime;
        $dateExt->modify( '+15 minutes' );
        $def = $dateExt->format("Y-m-d H:i:s");

        $db->Exec("INSERT INTO `TemporaryPasswords`(`id`,`UserId`,`Password`,`DateTime`)
                      VALUE ('{$guid}','{$userid}','{$new_pass}','{$def}'); ");

        return $new_pass;
    }

    public static function DeleteUnusedPasswords()
    {
        global $db;
        $dateExt   = new DateTime;
        $def = $dateExt->format("Y-m-d H:i:s");

        $db->Exec("delete from `TemporaryPasswords` where `DateTime`< CAST('{$def}' as datetime) ");
    }

    public static function DeletePasswords($login)
    {
        global $db;
        $d = $db->QueryOne("select id from Users where Login = '{$login}' ");
        $userid = $d["id"];

        $db->Exec("delete from `TemporaryPasswords` where UserId ='{$userid}' ");
    }


    public static function AlreadyLogin($userid)
    {
        global $db;
        $d = $db->QueryOne("select count(*) as col from Session where UserId = '{$userid}' ");
        return ($d["col"]>0?true:false);
    }

    public static function Delete($sessoin)
    {
        global $db;
        $db->Exec("delete from `Session` where id = '{$sessoin}' ");
    }

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
        $dateExt->modify( '+15 minutes' );
        $def = $dateExt->format("Y-m-d H:i:s");

        $db->Exec("INSERT INTO `Session`(`id`,`UserId`,`CreateDate`,`ExpireDate`)
                      VALUE ('{$guid}','{$userid}','{$date}','{$def}'); ");

        return $guid;
    }

    public static function DeleteUnusedSession()
    {
        global $db;
        $dateExt   = new DateTime;
        //$dateExt->modify( '+1 hour' );
        $def = $dateExt->format("Y-m-d H:i:s");

        $db->Exec("delete from `Session` where `ExpireDate`< CAST('{$def}' as datetime) ");
    }

}




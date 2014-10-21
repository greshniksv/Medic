<?php

abstract class Access{
    const User = 2;
    const Uploader = 1;
    const Admin = 0;
}

class Permission{

    public static function CheckSession()
    {
        global $db,$cookie;
        $r = $db->QueryOne("select count(*) as col from `Session` s where s.id='{$cookie}'");
        return ($r["col"]==0?false:true);
    }


    public static function Get()
    {
        global $db,$cookie;

        $r = $db->QueryOne("select u.Permission from `Session` s,`Users` u where u.`id`=s.`UserId` and s.id='{$cookie}'");
        return trim($r["Permission"]);
    }

    public static function Is()
    {
        global $permission;

        $arg_num = func_num_args();
        if($arg_num>0)
        {
            $arg_list = func_get_args();
            for ($i = 0; $i < $arg_num; $i++) if($permission==$arg_list[$i]) return true;
        }

        return false;
    }

    public static function Prolong()
    {
        global $db,$cookie;
        setcookie("session", $cookie, time()+900);
    }

}


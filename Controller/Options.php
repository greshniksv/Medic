<?php

switch($action)
{
    // ACTION =========================
    case "Set":
        $par = $_REQUEST["param"];
        $val = $_REQUEST["value"];

        $log->Write(basename(__FILE__,".php"),"Изменение конфигурации. Параметр:".$par." Значение: ".$val);

        $r = $db->QueryOne("select Value from Options where Param='{$par}' ");
        if($r["Value"]=="")
        {
            $db->Exec("insert into Options (Param,Value) values ('{$par}','{$val}' )");
        }
        else
        {
            $db->Exec("update Options set Value='{$val}' where Param='{$par}'");
        }
        die("ok");
        //Mvc::View(basename(__FILE__,".php"));
        break;

    case "Get":
        $par = $_REQUEST["param"];
        $r = $db->QueryOne("select Value from Options where Param='{$par}' ");
        die($r["Value"]);
        //Mvc::View(basename(__FILE__,".php"));
        break;

    default: echo "Action not found"; break;
}













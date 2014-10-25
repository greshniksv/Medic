<?php
/**
 * Created by PhpStorm.
 * User: GR
 * Date: 21.10.14
 * Time: 18:25
 */

switch ($action) {

    case "send":
        //echo Mail::Send();
        //Mvc::View(basename(__FILE__,".php"));
        break;

    default:
        echo "Action not found";
        break;
}
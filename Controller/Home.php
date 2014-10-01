<?php

switch($action)
{
    // ACTION =========================
    case "index":
        Mvc::View(basename(__FILE__,".php"));
    break;


    default: echo "Action not found"; break;
}













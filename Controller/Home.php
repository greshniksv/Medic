<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 13.09.14
 * Time: 20:57
 */

$guid = UUID::v4();

$db->Exec("INSERT INTO Users ( id, Login, Pasword, Hash, FirstName, LastName, Premition)
VALUE ( '{$guid}','Logf1', 'pas', 'H', 'f', 'l', '0' ) ");
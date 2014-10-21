<?php
/**
 * Created by PhpStorm.
 * User: GR
 * Date: 20.10.14
 * Time: 17:52
 */

class FixSearch
{
    public static function Go()
    {
        global $db;

        $db->Exec("delete from `ProductsSearch`;");

        $sql="select p.id as id, REPLACE(LOWER(concat(`NumberProvider`,p.`Name`,p.`FullName`,`BasicCharacteristics`,`Price`,`Rest`,pr.Name,pr.FullName,City,Address,Phone)),' ','') as data
 from `Products` p,`Provider` pr where pr.id = p.`ProviderId";
        $db->Query($sql);
        while($buf=$db->Fetch())
        {
            echo "data:".$buf['data']."\n";
            $data = iconv("windows-1251", "UTF-8//IGNORE", $buf['data']);
            echo "data:".$buf['data']."\n";
            $data = mb_strtolower($data, 'UTF-8');
            echo "data:".$data."\n";
            $db->Exec("insert into `ProductsSearch` (ProductId,SearchString) values ('".$buf['id']."','{$data}')");
        }
        $db->StopFetch();
    }
}

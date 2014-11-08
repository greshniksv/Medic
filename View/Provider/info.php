
<div id="provider_info">

    <table>
        <tr><td>Наименование: </td><td> <?php echo $DATA["Name"] ?> </td></tr>
        <tr><td>ФИО: </td><td> <?php echo $DATA["FullName"] ?> </td></tr>
        <tr><td>Город: </td><td> <?php echo $DATA["City"] ?> </td></tr>
        <tr><td>Адресс: </td><td> <?php echo $DATA["Address"] ?> </td></tr>
        <tr><td>Телефон: </td><td> <?php echo $DATA["Phone"] ?> </td></tr>
        <tr><td>ИИН: </td><td> <?php echo $DATA["IIN"] ?> </td></tr>
        <tr><td>Прайс: </td><td> <a href="<?php echo $DATA["InitialPrice"] ?>"> Скачать </a>  </td></tr>
    </table>

</div>



<?php
//$data = array("Name"=>$buf["Name"],"FullName"=>$buf["FullName"],
//    "City"=>$buf["City"],"Address"=>$buf["Address"],"Phone"=>$buf["Phone"],"IIN"=>$buf["IIN"]);
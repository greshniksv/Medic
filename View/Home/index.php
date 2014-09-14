<div id="brain">
    <img src="Image/brain_cr.jpg">
</div>

<div id="login2">
<div id="login" title="Авторизация">
    <table>
        <tr><td>Login:</td><td><input id="user" type="text" value="log" /></td></tr>
        <tr><td>Password:</td><td><input id="password" type="password" /></td></tr>
        <tr><td colspan="2" style="text-align: center"> <label id="login_result">bla bla</label> </td></tr>
        <tr><td colspan="2" style="text-align: center"> <input type="button" value="Login" onclick="login()"> </td></tr>
    </table>
</div>
</div>

<script type="application/javascript">

    $(function() {

        $( "#login" ).dialog({
            autoOpen: true,
            show: {
                effect: "bounce",
                duration: 1000
            },
            hide: {
                effect: "explode",
                duration: 1000
            }

        });

    });

    function login()
    {
        $( "#login" ).dialog("close");
        var log = $("#user").val();
        var pas = $("#password").val();
        $.get("index.php?c=Account&a=login&login="+log+"&password="+pas,function(data){

            if(data.trim()=="ok")
            {

                window.location = "index.php";

            }
            else
            {
                $( "#login" ).dialog("open");
                $("#login_result").fadeIn(1);
                $("#login_result").text("Incorrect login !");
                $("#login_result").fadeOut(9000);
            }

        });
    }


</script>


<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14.09.14
 * Time: 18:06
 */




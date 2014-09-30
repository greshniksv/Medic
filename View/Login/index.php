
<link rel="stylesheet" href="Css/login.css">

<div id="login_main">
    <div id="login_container" class="Absolute-Center">
    <div class="htext"> Авторизация </div>
    <div> <label for="user" class="tleft"> Пользователь </label>  <input id="user" type="text" value="log" /></div>
    <div> <label for="password" class="tleft"> Пароль </label>  <input id="password" type="password" value="" /></div>
    <div id="login_result">-</div>
    <div><input type="button" value="Login" onclick="login()"></div>
</div>

</div>

<script type="application/javascript">

    $(function() {

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
                $("#login").dialog("open");
                $("#login_result").text("Incorrect login !");
            }

        });
    }


</script>






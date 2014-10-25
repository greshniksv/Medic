
<link rel="stylesheet" href="Css/login.css">



<div id="login_main">
    <div id="login_container" class="Absolute-Center">

        <div class="panel panel-default">
            <div class="panel-heading">Авторизация</div>
            <div class="panel-body">

                <div class="input-group">
                    <span class="input-group-addon">#</span>
                    <input type="text" id="user" class="form-control" placeholder="Логин">
                </div>

                <div class="input-group">
                    <span class="input-group-addon">#</span>
                    <input type="password" id="password" class="form-control" placeholder="Пароль">
                </div>

                <button type="button" class="btn blue-button" onclick="login()">
                    <span><img src="images/ok.png"> </span> Авторизоваться
                </button>

            </div>
        </div>

        <div id="info" class="alert alert-danger" role="alert">  </div>

</div>

</div>

<script type="application/javascript">

    $(function() {
        $("input").keyup(function (e) {
            if (e.keyCode == 13) {
                login();
            }
        });

    });



    function login()
    {
        var log = $("#user").val();
        var pas = $("#password").val();

        if(pas.length<1)
        {
            $("#info").removeClass( "alert-success alert-danger" ).addClass( "alert-success" );
            $("#info").html("Отправка письма");
            $("#info").fadeIn(1000,function(){});

            // send password to mail
            $.get("index.php?c=Account&a=send_password&login="+log,function(data){

                $("#info").removeClass( "alert-success alert-danger" ).addClass( "alert-danger" );

                if(data.trim()=="ok"){
                    data="Письмо с паролем отправлено!";
                    $("#info").removeClass( "alert-success alert-danger" ).addClass( "alert-success" );
                }
                $("#info").html(data.trim());
                setTimeout(function(){
                    $("#info").fadeOut(1000,function(){});
                },3000);
            });
            return;
        }

        $.get("index.php?c=Account&a=login&login="+log+"&password="+pas,function(data){

            if(data.trim()=="ok")
            {
                window.location = "index.php";
            }
            else
            {
                $("#info").removeClass( "alert-success alert-danger" ).addClass( "alert-danger" );
                $("#info").html(data.trim());
                $("#info").fadeIn(1000,function(){
                    setTimeout(function(){
                        $("#info").fadeOut(1000,function(){});
                    },3000);
                });
            }

        });
    }


</script>






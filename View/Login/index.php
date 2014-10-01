
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

                <button type="button" class="btn btn-default btn" onclick="login()">
                    <span class="glyphicon glyphicon-hand-up"></span> Авторизоваться
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
                $("#info").html(data.trim());
                $("#login").dialog("open");
                $("#info").fadeIn(1000,function(){
                    setTimeout(function(){
                        $("#info").fadeOut(1000,function(){});
                    },1000);
                });
            }

        });
    }


</script>






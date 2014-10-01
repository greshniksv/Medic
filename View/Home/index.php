<link rel="stylesheet" href="Css/home.css">
<link rel="stylesheet" href="Css/button.css">

<div id="header" class="navbar navbar-default navbar-fixed-top" role="navigator">

    <ul class="nav navbar-nav navbar-right">

        <li id="status_off" class="active"><a href="#">Внимание! Сайт выключен.</a></li>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" > Админка <span class="caret"></span> </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#"  onclick="GoToUpload()">Загрузить прайс</a></li>
                <li><a href="#">Просмотр загр. прайса</a></li>
                <li><a href="#">Производители</a></li>
                <li><a href="#" onclick="GoToUsers()">Пользователи</a></li>
                <li><a href="#">Журнал активности</a></li>
                <li><a id="site_status" href="#">Выключить сайт</a></li>
            </ul>
        </li>

        <li id="exit" onclick="Exit()" class="active"><a href="#">Выход</a></li>

    </ul>
</div>

<div id="main_frame">

</div>



<script type="application/javascript">

    $(function() {
        GoToUpload();
        GetSiteStatus();

        $("#site_status").click(function(){
            ToggleSiteStatus();
        });

    });

    function ToggleSiteStatus()
    {
        $.get("index.php?c=Options&a=Get&param=Active",function(data){
            if(data.trim()=="true")
            {
                $.get("index.php?c=Options&a=Set&param=Active&value=false",function(data){ GetSiteStatus(); });
            }
            else
            {
                $.get("index.php?c=Options&a=Set&param=Active&value=true",function(data){ GetSiteStatus(); });
            }

        });
    }

    function GetSiteStatus()
    {
        $.get("index.php?c=Options&a=Get&param=Active",function(data){
            if(data.trim()=="true")
            {
                $("#status_off").fadeOut(1000);
                $("#site_status").html("Выключить сайт");
            }
            else
            {
                $("#status_off").fadeIn(1000);
                $("#site_status").html("Включить сайт");
            }
        });
    }

    function GoToUsers()
    {
        $.get("index.php?c=Users",function(data){
            $( "#main_frame").html(data);
        });
    }

    function GoToUpload()
    {
        $.get("index.php?c=UploadPrice",function(data){
            $( "#main_frame").html(data);
        });
    }

    function Exit() {
        document.cookie = 'session=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        window.location = "index.php";
    }

</script>







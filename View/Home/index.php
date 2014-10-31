<link rel="stylesheet" href="Css/home.css">
<link rel="stylesheet" href="Css/button.css">

<div id="header" class="navbar navbar-default navbar-fixed-top" role="navigator">

    <ul class="nav navbar-nav navbar-right">

        <li id="status_off" class="active"><a href="#" style="padding: 7px;"><img src="images/site_off.png"></a></li>

        <?php if(Permission::Is(Access::Admin,Access::Uploader)){ ?>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" > Админка <span class="caret"></span> </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#"  onclick="GoToSearch()">Поиск</a></li>
                <li><a href="#"  onclick="GoToUpload()">Загрузить прайс</a></li>
                <li><a href="#"  onclick="GoToChangePrice()">Изменить прайс</a></li>
                <li><a href="#" onclick="GoToProvider()">Поставщики</a></li>

                <?php if(Permission::Is(Access::Admin)){ ?>
                <li><a href="#" onclick="GoToUsers()">Пользователи</a></li>
                <li><a href="#" onclick="GoToLog()">Журнал активности</a></li>
                <?php }?>

                <li><a id="site_status" href="#">Выключить сайт</a></li>
            </ul>
        </li>
        <?php }?>

        <li id="exit" onclick="Exit()" class="active"><a href="#">Выход</a></li>

    </ul>
</div>

<div id="main_frame">

</div>


<script type="application/javascript">

    $(function() {
        //GoToChangePrice();
        GoToSearch();
        GetSiteStatus();

        $("#site_status").click(function(){
            ToggleSiteStatus();
        });
        window.setInterval(function(){ CheckSession(); },10000);
    });

    function CheckSession()
    {
        $.get("index.php?c=Account&a=check_session", function (data) {
            if(data.trim()!="alive")
            {
                alert("Время вышло.");
                Exit();
                //window.location = "index.php"
            }
        });
    }

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

    function GoToChangePrice()
    {
        $.get("index.php?c=ChangePrice",function(data){
            $( "#main_frame").html(data);
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

    function GoToLog()
    {
        $.get("index.php?c=Logs",function(data){
            $( "#main_frame").html(data);
        });
    }

    function GoToProvider()
    {
        $.get("index.php?c=Provider",function(data){
            $( "#main_frame").html(data);
        });
    }

    function GoToSearch()
    {
        $.get("index.php?c=Search",function(data){
            $( "#main_frame").html(data);
        });
    }

    function Exit() {
        $.get("index.php?c=Account&a=logout",function(data){
            document.cookie = 'session=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            window.location = "index.php";
        });
    }

</script>







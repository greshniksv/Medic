<link rel="stylesheet" href="Css/home.css">
<link rel="stylesheet" href="Css/button.css">

<div id="header" class="navbar navbar-default navbar-fixed-top" role="navigator">
    <ul class="nav navbar-nav navbar-right">

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" > Админка <span class="caret"></span> </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="">Загрузить прайс</a></li>
                <li><a href="#">Просмотр загр. прайса</a></li>
                <li><a href="#">Производители</a></li>
                <li><a href="#" onclick="GoToUsers()">Пользователи</a></li>
                <li><a href="#">Журнал активности</a></li>
                <li><a href="#">Выключить сайт</a></li>
            </ul>
        </li>

        <li id="exit" onclick="Exit()" class="active"><a href="#">Выход</a></li>

    </ul>
</div>

<div id="main_frame">
bla bla bla

</div>



<script type="application/javascript">

    $(function() {
        GoToUsers();
    });

    function GoToUsers()
    {
        $.get("index.php?c=Users",function(data){
            $( "#main_frame").html(data);
        });
    }

    function Exit() {
        document.cookie = 'session=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
        window.location = "index.php";
    }

</script>







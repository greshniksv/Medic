
<div class="fullscreen">
    <div id="users_form" class="absolute-center">
        <div id="users_list"></div>

        <div id="manage_buttons">
        <button type="button" class="btn btn-default " onclick="ShowAddForm()">
            <span class="glyphicon glyphicon-plus"></span>  Добавить </button>
        <button type="button" class="btn btn-default " onclick="ShowEditForm()">
            <span class="glyphicon glyphicon-pencil"></span>  Редактировать </button>
        <button type="button" class="btn btn-default " onclick="Remove()">
            <span class="glyphicon glyphicon-minus"></span>  Удалить </button>
        </div>
    </div>
</div>




<div id="remove_dialog" style="display: none" title="Добавить пользователя">

</div>

<div id="add_dialog" style="display: none" title="Добавить пользователя">

    <input id="userid" type="hidden">
    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="login" onchange="" class="form-control" placeholder="Логин">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="password" id="pass" class="form-control" placeholder=Пароль>
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="name" class="form-control" placeholder="Имя">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="last" class="form-control" placeholder="Фамилия">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <!--<input type="text" class="form-control" placeholder="Доступ">-->
        <select id="perm" class="form-control">
            <option value="0">Администратор</option>
            <option value="1">Журналист</option>
            <option value="2">Пользователь</option>
        </select>
    </div>

</div>



<script type="application/javascript">

    $(function() {
        GoToUsers();
    });

    function GoToUsers()
    {
        $.get("index.php?c=Users&a=UserList",function(data){
            $( "#users_list").html(data);
        });
    }

    function ShowAddForm()
    {
        $("#login").val("");
        $("#name").val("");
        $("#last").val("");
        $("#pass").val("");
        $("#perm").val(2);

        $( "#add_dialog" ).dialog({
            height:300,
            width: 300,
            modal: true,
            buttons: {
                "Добавить": function() {

                    $.get("index.php?c=Users&a=GetIdByLogin&login="+$("#login").val(),function(data){
                        if(data.length<5)
                        {
                            var url="&login="+$("#login").val();
                            url+="&password="+$("#pass").val();
                            url+="&firstname="+$("#name").val();
                            url+="&lastname="+$("#last").val();
                            url+="&permission="+$("#perm").val();


                            $.get("index.php?c=Users&a=Create"+url,function(data){
                                if(data.trim()!="ok")
                                {
                                    alert(data);
                                }
                            });

                            GoToUsers();
                            $( "#add_dialog" ).dialog( "close" );
                        }
                        else
                        {
                            alert("Такой логин уже существует!");
                        }
                    });


                },
                "Закрыть": function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    }





    function ShowEditForm()
    {
        var old_login= $("tr.active").find('td:eq(0)').text();
        if(old_login.length<1)
        {
            alert("Ничего не выбрано!");
            return;
        }

        $.get("index.php?c=Users&a=GetIdByLogin&login="+old_login,function(data){
            if(data.left<5)
            {
                alert("Пользователь не найден!");
                return;
            }
            else
            {
                $("#userid").val(data.trim());
            }

            $("#login").val($("tr.active").find('td:eq(0)').text());
            $("#name").val($("tr.active").find('td:eq(1)').text());
            $("#last").val($("tr.active").find('td:eq(2)').text());
            $("#pass").val("");
            var per = $("tr.active").find('td:eq(3)').text();
            switch (per)
            {
                case "Администратор": $("#perm").val(0); break;
                case "Журналист": $("#perm").val(1); break;
                default: $("#perm").val(2); break;
            }


            $( "#add_dialog" ).dialog({
                height:300,
                width: 300,
                modal: true,
                buttons: {
                    "Добавить": function() {

                        $.get("index.php?c=Users&a=GetIdByLogin&login="+$("#login").val(),function(data){
                            if(data.length<5 || old_login == $("#login").val())
                            {
                                var url="&login="+$("#login").val();
                                url+="&userid="+$("#userid").val();
                                url+="&password="+$("#pass").val();
                                url+="&firstname="+$("#name").val();
                                url+="&lastname="+$("#last").val();
                                url+="&permission="+$("#perm").val();

                                $.get("index.php?c=Users&a=Edit"+url,function(data){
                                    if(data.trim()!="ok")
                                    {
                                        alert(data);
                                    }
                                });

                                GoToUsers();
                                $( "#add_dialog" ).dialog( "close" );
                            }
                            else
                            {
                                alert("Такой логин уже существует!");
                            }
                        });

                    },
                    "Закрыть": function() {
                        $( this ).dialog( "close" );
                    }
                }
            });

        });
    }

    function Remove()
    {
        var login = $("tr.active").find('td:eq(0)').text();
        var name = $("tr.active").find('td:eq(1)').text();
        var last = $("tr.active").find('td:eq(2)').text();

        if(login.length<1)
        {
            alert("Ничего не выбрано!");
            return;
        }
        $("#remove_dialog").html("Вы действительно хотите удалить пользователя ?<br><hr>"+
            login+" : "+name+" "+last);

        $( "#remove_dialog" ).dialog({
            height:250,
            width: 350,
            modal: true,
            buttons: {
                "Удалить": function() {

                    $.get("index.php?c=Users&a=GetIdByLogin&login="+login,function(data){
                        var userid = data.trim();
                        $.get("index.php?c=Users&a=Delete&userid="+userid,function(data){
                            if(data.trim()!="ok")
                            {
                                alert(data.trim())
                            }

                            GoToUsers();
                            $( "#remove_dialog" ).dialog( "close" );
                        });
                    });

                },
                "Закрыть": function() {
                    $( this ).dialog( "close" );
                }
            }
        });



    }


</script>


<?php



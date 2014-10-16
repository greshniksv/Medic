<div class="fullscreen">
    <div id="users_form" class="absolute-center">
        <div id="users_list" class="col-xs-10"></div>

        <div id="manage_buttons" class="col-xs-2">
            <button type="button" class="btn btn-default " onclick="ShowAddForm()">
                <span class="glyphicon glyphicon-plus"></span> Добавить
            </button>
            <button type="button" class="btn btn-default " onclick="ShowEditForm()">
                <span class="glyphicon glyphicon-pencil"></span> Редактировать
            </button>
            <button type="button" class="btn btn-default " onclick="Remove()">
                <span class="glyphicon glyphicon-minus"></span> Удалить
            </button>
        </div>
    </div>
</div>


<div id="remove_dialog" style="display: none" title="Удалить пользователя"></div>

<div id="add_dialog" style="display: none" title="Добавить пользователя">

    <input id="manufid" type="hidden">

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="name" onchange="" class="form-control" placeholder="Наименование">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="fullname" class="form-control" placeholder=ФИО>
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="city" class="form-control" placeholder="Город">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="address" class="form-control" placeholder="Адресс">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="phone" class="form-control" placeholder="Телефон">
    </div>

</div>


<script type="application/javascript">

    $(function () {
        DrawManuf();
    });

    function DrawManuf() {
        $.get("index.php?c=Provider&a=get_list", function (data) {
            $("#users_list").html(data);
        });
    }

    function ShowAddForm() {
        $("#name").val("");
        $("#fullname").val("");
        $("#city").val("");
        $("#address").val("");
        $("#phone").val("");

        $("#add_dialog").dialog({
            height: 350,
            width: 350,
            modal: true,
            buttons: [
                {
                    text: "  Добавить",
                    "class": 'add-button',
                    click: function () {

                        $.get("index.php?c=Provider&a=GetIdByName&name=" + $("#name").val(), function (data) {
                            if (data.length < 5) {
                                var url = "&name=" + $("#name").val();
                                url += "&fullname=" + $("#fullname").val();
                                url += "&city=" + $("#city").val();
                                url += "&address=" + $("#address").val();
                                url += "&phone=" + $("#phone").val();


                                $.get("index.php?c=Manufacturer&a=Create" + url, function (data) {
                                    if (data.trim() != "ok") {
                                        alert(data);
                                    }
                                });

                                DrawManuf();
                                $("#add_dialog").dialog("close");
                            }
                            else {
                                alert("Такой поставщик уже существует!");
                            }
                        });

                    }
                },
                {
                    text: "  Закрыть",
                    "class": 'cancel-button',
                    click: function () {
                        $(this).dialog("close");
                    }
                }

            ]

        });
    }


    function ShowEditForm() {
        var old_name = $("tr.active").find('td:eq(0)').text();
        if (old_name.length < 1) {
            alert("Ничего не выбрано!");
            return;
        }

        $.get("index.php?c=Provider&a=GetIdByName&name=" + old_name, function (data) {
            if (data.length < 5) {
                alert("Пользователь не найден!");
                return;
            }
            var old_id = data.trim();

            $("#name").val($("tr.active").find('td:eq(0)').text());
            $("#fullname").val($("tr.active").find('td:eq(1)').text());
            $("#city").val($("tr.active").find('td:eq(2)').text());
            $("#address").val($("tr.active").find('td:eq(3)').text());
            $("#phone").val($("tr.active").find('td:eq(4)').text());

            $("#add_dialog").dialog({
                height: 350,
                width: 350,
                modal: true,
                buttons:
                [
                    {
                        text: "  Изменить",
                        "class": '',
                        click: function () {

                            $.get("index.php?c=Provider&a=GetIdByName&name=" + $("#name").val(), function (data) {
                                if (data.length < 5 || old_name == $("#name").val()) {
                                    var url = "&name=" + $("#name").val();
                                    url += "&manufid=" + old_id.trim();
                                    url += "&fullname=" + $("#fullname").val();
                                    url += "&city=" + $("#city").val();
                                    url += "&address=" + $("#address").val();
                                    url += "&phone=" + $("#phone").val();

                                    $.get("index.php?c=Manufacturer&a=Edit" + url, function (data) {
                                        if (data.trim() != "ok") {
                                            alert(data);
                                        }
                                    });

                                    DrawManuf();
                                    $("#add_dialog").dialog("close");
                                }
                                else {
                                    alert("Такой поставщик уже существует!");
                                }
                            });

                        }
                    }
                    ,
                    {
                        text: "  Закрыть",
                        "class": 'cancel-button',
                        click: function () {
                            $(this).dialog("close");
                        }

                    }

                ]

            });

        });
    }

    function Remove() {
        var name = $("tr.active").find('td:eq(0)').text();
        var fillname = $("tr.active").find('td:eq(1)').text();

        if (name.length < 1) {
            alert("Ничего не выбрано!");
            return;
        }
        $("#remove_dialog").html("Вы действительно хотите удалить поставщика ?<br><hr>" +
            name + " : " + fillname);

        $("#remove_dialog").dialog({
            height: 250,
            width: 350,
            modal: true,
            buttons:
            [
                {
                    text: "  Удалить",
                    "class": '',
                    click: function () {
                        $.get("index.php?c=Provider&a=GetIdByName&name=" + name, function (data) {
                            var manufid = data.trim();
                            $.get("index.php?c=Provider&a=Delete&manufid=" + manufid, function (data) {
                                if (data.trim() != "ok") {
                                    alert(data.trim())
                                }

                                DrawManuf();
                                $("#remove_dialog").dialog("close");
                            });
                        });
                    }
                },
                {
                    text: "  Закрыть",
                    "class": 'cancel-button',
                    click: function () {
                        $(this).dialog("close");
                    }
                }
            ]

        });

    }


</script>


<?php



<div class="fullscreen">

    <table style="width: 100%;margin-top: 60px;">
        <tr>
            <td valign="top" style="width: 300px">
        <tr>
            <td>
                <div id="search_manage_buttons">

                    <div class="row" style=" display: inline-block; width: 80%;">

                        <div class="col-xs-3">

                            <select id="combobox" class="form-control">
                                <option selected value="0">Поставщик</option>
                            </select>

                        </div>


                        <div class="col-xs-6">

                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                                <input type="text" id="search" class="form-control" placeholder="Начать поиск">
                            </div>

                        </div>

                        <div class="col-xs-2">

                            <button onclick="DrawSearchList()" type="" class="btn blue-button">
                                <span class="glyphicon glyphicon-search"></span> Начать поиск
                            </button>

                        </div>

                    </div>


                </div>

                <div id="users_list"></div>

                <div id="manage_buttons_change" style="display: inline-block">
                    <div></div>

                    <button type="button" class="btn blue-button " onclick="ShowAddForm()">
                        <span> <img src="images/add.png"> </span> Добавить
                    </button>
                    <button type="button" class="btn green-button " onclick="ShowEditForm()">
                        <span><img src="images/edit.png"></span> Редактировать
                    </button>
                    <button type="button" class="btn blue-button " onclick="Remove()">
                        <span><img src="images/del.png"></span> Удалить
                    </button>

                </div>

            </td>
        </tr>
    </table>

</div>


<div id="remove_dialog" style="display: none" title="Добавить пользователя">

</div>

<div id="add_dialog" style="display: none" title="Добавить пользователя">

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="login" onchange="" class="form-control" placeholder="Код товара в базе постащика">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="password" id="pass" class="form-control" placeholder="Наименование товара">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="name" class="form-control" placeholder="Торговое наименование">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="last" class="form-control" placeholder="Основные характеристики">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="mail" class="form-control" placeholder="Цена в рублях">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="mail" class="form-control" placeholder="Остаток">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>

        <select id="perm" class="form-control">
            <option value="0">Администратор</option>
            <option value="1">Журналист</option>
            <option value="2">Пользователь</option>
        </select>
    </div>

</div>


<script type="application/javascript">
var files;


function ShowAddForm() {
    $("#login").val("");
    $("#name").val("");
    $("#last").val("");
    $("#pass").val("");
    $("#perm").val(2);
    $("#mail").val("");

    $("#add_dialog").dialog({
        height: 350,
        width: 350,
        modal: true,
        buttons: [
            {
                text: "  Добавить",
                "class": 'add-button',
                click: function () {
                    $.get("index.php?c=ChangePrice&a=GetIdByLogin&login=" + $("#login").val(), function (data) {
                        if (data.length < 5) {
                            var url = "&login=" + $("#login").val();
                            url += "&password=" + $("#pass").val();
                            url += "&firstname=" + $("#name").val();
                            url += "&lastname=" + $("#last").val();
                            url += "&permission=" + $("#perm").val();
                            url += "&mail=" + $("#mail").val();


                            $.get("index.php?c=ChangePrice&a=Create" + url, function (data) {
                                if (data.trim() != "ok") {
                                    alert(data);
                                }
                                DrawUsers();
                            });

                            $("#add_dialog").dialog("close");
                        }
                        else {
                            alert("Такой логин уже существует!");
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
    var id = $("tr.active").find('td:eq(0)').find('p').attr('id');
    if (id.length < 1) {
        alert("Ничего не выбрано!");
        return;
    }

    $("#login").val($("tr.active").find('td:eq(0)').text());
    $("#name").val($("tr.active").find('td:eq(1)').text());
    $("#last").val($("tr.active").find('td:eq(2)').text());
    $("#mail").val($("tr.active").find('td:eq(4)').text());
    $("#pass").val("");
    var per = $("tr.active").find('td:eq(3)').text();
    switch (per) {
        case "Администратор":
            $("#perm").val(0);
            break;
        case "Журналист":
            $("#perm").val(1);
            break;
        default:
            $("#perm").val(2);
            break;
    }


    $("#add_dialog").dialog({
        height: 350,
        width: 350,
        modal: true,
        buttons: [
            {
                text: "  Изменить",
                "class": 'edit-button',
                click: function () {
                    var url = "&login=" + $("#login").val();
                    url += "&userid=" + $("#userid").val();
                    url += "&password=" + $("#pass").val();
                    url += "&firstname=" + $("#name").val();
                    url += "&lastname=" + $("#last").val();
                    url += "&permission=" + $("#perm").val();
                    url += "&mail=" + $("#mail").val();

                    $.get("index.php?c=ChangePrice&a=Edit" + url, function (data) {
                        if (data.trim() != "ok") {
                            alert(data);
                        }
                        DrawUsers();
                    });
                    $("#add_dialog").dialog("close");
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

function Remove() {
    var login = $("tr.active").find('td:eq(0)').text();
    var name = $("tr.active").find('td:eq(1)').text();
    var last = $("tr.active").find('td:eq(2)').text();

    if (login.length < 1) {
        alert("Ничего не выбрано!");
        return;
    }
    $("#remove_dialog").html("Вы действительно хотите удалить пользователя ?<br><hr>" +
        login + " : " + name + " " + last);

    $("#remove_dialog").dialog({
        height: 250,
        width: 350,
        modal: true,
        buttons: [
            {
                text: "  Удалить",
                "class": 'delete-button',
                click: function () {
                    $.get("index.php?c=ChangePrice&a=GetIdByLogin&login=" + login, function (data) {
                        var userid = data.trim();
                        $.get("index.php?c=ChangePrice&a=Delete&userid=" + userid, function (data) {
                            if (data.trim() != "ok") {
                                alert(data.trim())
                            }

                            DrawUsers();
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


(function ($) {
    $.widget("custom.combobox", {
        _create: function () {
            this.wrapper = $("<div id='con' class='input-group'><span class='input-group-addon'>#</span>")
                //.addClass( "custom-combobox" )
                //.append("<div id='con' class='input-group'><span class='input-group-addon'>#</span>")
                .insertAfter(this.element);

            this.element.hide();
            this._createAutocomplete();
            //this._createShowAllButton();
        },

        _createAutocomplete: function () {
            var selected = this.element.children(":selected"),
                value = selected.val() ? selected.text() : "";

            this.input = $("<input>")
                .appendTo(this.wrapper)
                .val(value)
                .attr("title", "")
                //.addClass( "custom-combobox-input ui-widget ui-state-default ui-corner-left" )
                .addClass("form-control")
                .autocomplete({
                    delay: 0,
                    minLength: 0,
                    source: $.proxy(this, "_source")
                }).click(function () {
                    $("input").autocomplete("search", "");
                });

            this._on(this.input, {
                autocompleteselect: function (event, ui) {
                    ui.item.option.selected = true;
                    this._trigger("select", event, {
                        item: ui.item.option
                    });
                },

                autocompletechange: "_removeIfInvalid"
            });

            //this.wrapper.appendTo("#con");
            //this.wrapper.append("</div>");

        },

        _source: function (request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response(this.element.children("option").map(function () {
                var text = $(this).text();
                if (this.value && ( !request.term || matcher.test(text) ))
                    return {
                        label: text,
                        value: text,
                        option: this
                    };
            }));
        },

        _removeIfInvalid: function (event, ui) {

            // Selected an item, nothing to do
            if (ui.item) {
                return;
            }

            // Search for a match (case-insensitive)
            var value = this.input.val(),
                valueLowerCase = value.toLowerCase(),
                valid = false;
            this.element.children("option").each(function () {
                if ($(this).text().toLowerCase() === valueLowerCase) {
                    this.selected = valid = true;
                    return false;
                }
            });

            // Found a match, nothing to do
            if (valid) {
                return;
            }

            // Remove invalid value
            this.input
                .val("")
                .attr("title", value + " didn't match any item")
                .tooltip("open");
            this.element.val("");
            this._delay(function () {
                this.input.tooltip("close").attr("title", "");
            }, 2500);
            this.input.autocomplete("instance").term = "";
        },

        _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        }
    });
})(jQuery);


$(function () {

    $("#combobox").combobox();

    DrawSearchList();
    GetProviderList();

    $("#search").keyup(function (e) {
        if (e.keyCode == 13) {
            DrawSearchList();
        }
    });

});


function DrawSearchList() {
    $.get("index.php?c=ChangePrice&a=get_list&search=" + $("#search").val().toLowerCase(), function (data) {
        $("#users_list").html(data);
        $("#example").css("width", "0");
    });
}

function GetProviderList() {
    $.get("index.php?c=Provider&a=get_list_data", function (data) {
        var obj = JSON.parse(data);
        for (var i = 0; i < obj.data.length; i++) {
            $("#combobox").append("<option value='" + obj.data[i].id + "'>" + obj.data[i].Name + "</option>");
        }
    });
}


</script>



<?php

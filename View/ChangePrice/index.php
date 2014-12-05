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

                <table>
                    <tr>
                        <td style="width: 80%">
                            <div id="users_list"></div>
                        </td>
                        <td style="width: 20%">
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

            </td>
        </tr>
    </table>

</div>


<div id="remove_dialog" style="display: none" title="Удалить товар">

</div>

<div id="add_dialog" style="display: none" title="Добавить товар">

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="prov_code" onchange="" class="form-control" placeholder="Код товара в базе постащика">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="pname" class="form-control" placeholder="Наименование товара">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="tpname" class="form-control" placeholder="Торговое наименование">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="prop" class="form-control" placeholder="Основные характеристики">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="unit" class="form-control" placeholder="Единица измерения">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="price" class="form-control" placeholder="Цена в рублях">
    </div>

    <div class="input-group">
        <span class="input-group-addon">#</span>
        <input type="text" id="rest" class="form-control" placeholder="Остаток">
    </div>

    <select id="prov" class="form-control">
        <option selected value="0">Поставщик</option>
    </select>

</div>


<script type="application/javascript">
var files;


function ShowAddForm() {
    $("#prov_code").val("");
    $("#pname").val("");
    $("#tpname").val("");
    $("#prop").val("");
    $("#unit").val("");
    $("#price").val("");
    $("#rest").val("");
    $("#prov").val(0);

    $("#add_dialog").dialog({
        height: 400,
        width: 450,
        modal: true,
        buttons: [
            {
                text: "  Добавить",
                "class": 'add-button',
                click: function () {
                    var url = "&prov_code=" + $("#prov_code").val();
                    url += "&pname=" + $("#pname").val();
                    url += "&tpname=" + $("#tpname").val();
                    url += "&prop=" + $("#prop").val();
                    url += "&price=" + $("#price").val();
                    url += "&rest=" + $("#rest").val();
                    url += "&prov=" + $("#prov").val();
                    url += "&unit=" + $("#unit").val();

                    $.get("index.php?c=ChangePrice&a=Create" + url, function (data) {
                        if (data.trim() != "ok") {
                            alert(data);
                        }
                        DrawSearchList();
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
        ],
        focus: function () {
            var dialogIndex = parseInt($(this).parent().css("z-index"), 10);
            $(this).find(".ui-autocomplete-input").each(function (i, obj) {
                $(obj).autocomplete("widget").css("z-index", dialogIndex + 1)
            });
        }

    });
}


function ShowEditForm() {
    var id = $("tr.active").find('td:eq(0)').find('p').attr('id');
    if (id.length < 1) {
        alert("Ничего не выбрано!");
        return;
    }

    $("#prov_code").val($("tr.active").find('td:eq(1)').text());
    $("#pname").val($("tr.active").find('td:eq(2)').text());
    $("#tpname").val($("tr.active").find('td:eq(3)').text());
    $("#prop").val($("tr.active").find('td:eq(4)').text());
    $("#unit").val($("tr.active").find('td:eq(5)').text());
    $("#price").val($("tr.active").find('td:eq(6)').text());
    $("#rest").val($("tr.active").find('td:eq(7)').text());
    //$("#prov").val($("tr.active").find('td:eq(6)').text());

    // set auto complete
    var name = $("tr.active").find('td:eq(8)').find('p').text();
    var val =$("tr.active").find('td:eq(8)').find('p').attr('id')
    $('#prov').combobox('autocomplete', name,val);

    $("#add_dialog").dialog({
        height: 400,
        width: 450,
        modal: true,
        buttons: [
            {
                text: "  Изменить",
                "class": 'edit-button',
                click: function () {
                    var url = "&prov_code=" + $("#prov_code").val();
                    url += "&pname=" + $("#pname").val();
                    url += "&tpname=" + $("#tpname").val();
                    url += "&prop=" + $("#prop").val();
                    url += "&price=" + $("#price").val();
                    url += "&permission=" + $("#perm").val();
                    url += "&rest=" + $("#rest").val();
                    url += "&prov=" + $('#prov').val();
                    url += "&unit=" + $('#unit').val();
                    url += "&id=" + id;


                    $.get("index.php?c=ChangePrice&a=Edit" + url, function (data) {
                        if (data.trim() != "ok") {
                            alert(data);
                        }
                        DrawSearchList();
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
        ],
        focus: function () {
        var dialogIndex = parseInt($(this).parent().css("z-index"), 10);
        $(this).find(".ui-autocomplete-input").each(function (i, obj) {
            $(obj).autocomplete("widget").css("z-index", dialogIndex + 1)
        });
    }

    });

}

function Remove() {
    var code = $("tr.active").find('td:eq(1)').text();
    var name = $("tr.active").find('td:eq(2)').text();
    var tname = $("tr.active").find('td:eq(3)').text();

    var id = $("tr.active").find('td:eq(0)').find('p').attr('id');
    if (id.length < 1) {
        alert("Ничего не выбрано!");
        return;
    }

    $("#remove_dialog").html("Вы действительно хотите удалить продукт ?<br /><hr>" +
        "Код: "+code + " <br />- " + name + " <br />- " + tname);

    $("#remove_dialog").dialog({
        height: 300,
        width: 360,
        modal: true,
        buttons: [
            {
                text: "  Удалить",
                "class": 'delete-button',
                click: function () {
                    $.get("index.php?c=ChangePrice&a=Delete&id=" + id, function (data) {
                        if (data.trim() != "ok") {
                            alert(data.trim())
                        }

                        DrawSearchList();
                        $("#remove_dialog").dialog("close");
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
                .insertAfter(this.element);

            this.element.hide();
            this._createAutocomplete();
            //this._createShowAllButton();
        },

        _createAutocomplete: function () {
            var selected = this.element.children(":selected"),
                value = selected.val() ? selected.text() : "";
            var id = Math.floor((Math.random() * 1000) + 1);
            this.input = $("<input id='inp_"+id+"'>")
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
                    $("#inp_"+id+"").autocomplete("search", "");
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
        autocomplete : function(value,key) {
            this.element.val(key);
            this.input.val(value);
        },

        _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        }
    });
})(jQuery);


$(function () {

    $("#combobox").combobox({
        select: function (event, ui) {
            //alert("the selec event has fired!");
            DrawSearchList();
        }
    });
    $("#prov").combobox();


    DrawSearchList();
    GetProviderList();

    $("#search").keyup(function (e) {
        if (e.keyCode == 13) {
            DrawSearchList();
        }
    });

});


function DrawSearchList() {
    $.get("index.php?c=ChangePrice&a=get_list&search=" + $("#search").val().toLowerCase()+"&provider="+$("#combobox").val(),
        function (data) {
        $("#users_list").html(data);
            if($(".dataTables_empty").lengt)
            {
                $("#example").css("width", "0");
            }
    });
}

function GetProviderList() {
    $.get("index.php?c=Provider&a=get_list_data", function (data) {
        var obj = JSON.parse(data);
        for (var i = 0; i < obj.data.length; i++) {
            $("#combobox").append("<option value='" + obj.data[i].id + "'>" + obj.data[i].Name + "</option>");
            $("#prov").append("<option value='" + obj.data[i].id + "'>" + obj.data[i].Name + "</option>");
        }
    });
}


</script>



<?php

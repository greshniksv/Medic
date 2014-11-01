
<div class="fullscreen">

    <table style="width: 100%;margin-top: 60px;" ><tr><td valign="top" style="width: 300px">

                <div id="advanced_search" class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Расширенный поиск</div>
                        <div class="panel-body">

                            <div class="input-group">
                                <span class="input-group-addon">#</span>
                                <input type="text" id="code" class="form-control" placeholder="Код в базе постащика">
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">#</span>
                                <input type="text" id="pname" class="form-control" placeholder="Наименование товара">
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">#</span>
                                <input type="text" id="fname" class="form-control" placeholder="Торговое наименование">
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">#</span>
                                <input type="text" id="prop" class="form-control" placeholder="Основные характеристики">
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">#</span>
                                <input type="text" id="price" class="form-control" placeholder="Цена">
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">#</span>
                                <input type="text" id="rest" class="form-control" placeholder="Остаток">
                            </div>

                            <div class="input-group" style="width: 100%">
                                <select id="provider" class="form-control">
                                    <option selected value="0">Все постащики</option>
                                </select>
                            </div>

                            <button type="button" class="btn blue-button col-xs-12" onclick="DrawSearchList()">
                                <span class="glyphicon glyphicon-search"></span> Найти
                            </button>

                        </div>
                    </div>
                </div>

                <div id="advanced_search" class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Корзина для экспорта</div>
                        <div class="panel-body">

                            <p id="basket_selected" style="text-align: center" > Выбрано 0 позиций </p>

                            <button type="button" class="btn blue-button col-xs-12" onclick="AddRow()">
                                <span class="glyphicon glyphicon-plus"></span> Добавить
                            </button>

                            <button type="button" class="btn blue-button col-xs-12" onclick="Show()">
                                <span class="glyphicon glyphicon-search"></span> Просмотреть
                            </button>

                            <button type="button" class="btn blue-button col-xs-12" onclick="Clear()">
                                <span class="glyphicon glyphicon-trash"></span> Очистить
                            </button>

                            <button type="button" class="btn blue-button col-xs-12" onclick="DrawSearchList()">
                                <span class="glyphicon glyphicon-download"></span> Экспорт
                            </button>

                        </div>
                    </div>
                </div>


    </td><td  valign="top">

                <div id="search_manage_buttons">

                    <div class="row">

                        <div class="col-xs-10">

                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                                <input type="text" id="serach" class="form-control" placeholder="Начать поиск">
                            </div>

                        </div>

                        <div class="col-xs-2">

                            <button onclick="DrawSearchList()" type="" class="btn blue-button">
                                <span class="glyphicon glyphicon-search"></span> Начать поиск </button>

                        </div>

                    </div>


                </div>

                <div id="search_list"></div>

    </td></tr>
    </table>



</div>


<div id="dialog-confirm" style="display: none" title="Очистить корзину?">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Вы действительно хотите удалить все товары из корзины ?</p>
</div>

<div id="dialog-show" style="display: none; z-index: " title="Корзина">
<div id="container"></div>
</div>



<script type="application/javascript">
    var files;
    var basket = new Array();

    function AddRow()
    {
        $("input:checked").parent().parent().each(function(i,e){
            var i = new Object();
            i.id=$(e).find('td:eq(0)').find('input').attr('id');
            i.codemk = $(e).find('td:eq(1)').text();
            i.code = $(e).find('td:eq(2)').text();
            i.name = $(e).find('td:eq(3)').text();
            i.tname = $(e).find('td:eq(4)').text();
            i.char = $(e).find('td:eq(5)').text();
            i.price = $(e).find('td:eq(6)').text();
            i.rest = $(e).find('td:eq(7)').text();
            i.prov = $(e).find('td:eq(8)').text();
            basket.push(i);

            $(e).find('td:eq(0)').find('input').prop( "checked", false );

            console.log(JSON.stringify(basket));
        });

        $(basket_selected).text("Выбрано "+basket.length+" позиций ")
    }

    function Clear()
    {
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height:200,
            width:350,
            modal: true,
            buttons: [
                {
                    text: "  Удалить",
                    "class": 'delete-button',
                    click: function () {
                        basket = new Array();
                        AddRow();
                        $(this).dialog("close");
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

    function Show()
    {
        var o="";
        for(var i=0;i<30;i++)
        {
            o+=i+"<br />";
        }


        $("#container").html(o);


        $( "#dialog-show" ).dialog({
            resizable: false,
            height:600,
            width:1200,
            modal: true,
            buttons: [
                {
                    text: "  Очистить",
                    "class": 'delete-button',
                    click: function () {
                        basket = new Array();
                        AddRow();
                        $(this).dialog("close");
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
        $('.ui-dialog').css('z-index',10001);
    }

    (function ($) {
        $.widget("custom.combobox", {
            _create: function () {
                this.wrapper = $("<div id='con' style='width: 100%' class='input-group'><span class='input-group-addon'>#</span>")
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





    $(function() {

        $("#provider").combobox();

        DrawSearchList();
        GetProviderList();

        $("#serach").keyup(function (e) {
            if (e.keyCode == 13) {
                DrawSearchList();
            }
        });

    });

    function DrawSearchListExtend()
    {
        var adv = "&fname="+$("#fname").val()+"&provider="+
            ($("#provider").val()==null ||$("#provider").val()==0 ?"":$("#provider").val())+
            "&price="+$("#price").val()+"&rest="+$("#rest").val();

        $.get("index.php?c=Search&a=get_list&search="+$("#serach").val().toLowerCase()+adv,function(data){
            $( "#search_list").html(data);
            //$( "#search_list").css("width","0");
        });
    }



    function DrawSearchList()
    {
        var adv = "&fname="+$("#fname").val()+"&provider="+
            ($("#provider").val()==null ||$("#provider").val()==0 ?"":$("#provider").val())+
            "&price="+$("#price").val()+"&rest="+$("#rest").val();

        $.get("index.php?c=Search&a=get_list&search="+$("#serach").val().toLowerCase(),function(data){
            $( "#search_list").html(data);
            //$( "#search_list").css("width","0");
        });
    }

    function GetProviderList()
    {
        $.get("index.php?c=Provider&a=get_list_data",function(data){
            var obj = JSON.parse(data);
            for(var i=0;i<obj.data.length;i++)
            {
                $("#provider").append("<option value='"+obj.data[i].id+"'>"+obj.data[i].Name+"</option>");
            }
        });
    }


</script>



<?php

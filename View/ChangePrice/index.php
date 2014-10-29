
<div class="fullscreen">

    <table style="width: 100%;margin-top: 60px;" ><tr><td valign="top" style="width: 300px"><tr><td>
                <div id="search_manage_buttons">

                    <div class="row" style=" display: inline-block; width: 80%;">

                        <div class="col-xs-3">

                                <select id="combobox" class="form-control">
                                    <option selected value="0">Все постащики</option>
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
                                <span class="glyphicon glyphicon-search"></span> Начать поиск </button>

                        </div>

                    </div>


                </div>

                <div id="users_list"></div>

                <div id="manage_buttons_change" style="display: inline-block">
                    <div> </div>

                    <button type="button" class="btn blue-button " onclick="ShowAddForm()">
                        <span> <img src="images/add.png"> </span>  Добавить </button>
                    <button type="button" class="btn green-button " onclick="ShowEditForm()">
                        <span><img src="images/edit.png"></span>  Редактировать </button>
                    <button type="button" class="btn blue-button " onclick="Remove()">
                        <span><img src="images/del.png"></span>  Удалить </button>

                </div>

    </td></tr></table>

</div>




<script type="application/javascript">
    var files;



    (function( $ ) {
        $.widget( "custom.combobox", {
            _create: function() {
                this.wrapper = $( "<span>" )
                    //.addClass( "custom-combobox" )
                    .insertAfter("<span class='input-group-addon'>#</span>")
                    .insertAfter( this.element );

                this.element.hide();
                this._createAutocomplete();
                //this._createShowAllButton();
            },

            _createAutocomplete: function() {
                var selected = this.element.children( ":selected" ),
                    value = selected.val() ? selected.text() : "";

                this.input = $( "<input>" )
                    .appendTo( this.wrapper )
                    .val( value )
                    .attr( "title", "" )
                    //.addClass( "custom-combobox-input ui-widget ui-state-default ui-corner-left" )
                    .addClass("form-control")
                    .autocomplete({
                        delay: 0,
                        minLength: 0,
                        source: $.proxy( this, "_source" )
                    }).click(function() {
                        $("input").autocomplete("search", "");
                    });

                this._on( this.input, {
                    autocompleteselect: function( event, ui ) {
                        ui.item.option.selected = true;
                        this._trigger( "select", event, {
                            item: ui.item.option
                        });
                    },

                    autocompletechange: "_removeIfInvalid"
                });
            },

            _source: function( request, response ) {
                var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                response( this.element.children( "option" ).map(function() {
                    var text = $( this ).text();
                    if ( this.value && ( !request.term || matcher.test(text) ) )
                        return {
                            label: text,
                            value: text,
                            option: this
                        };
                }) );
            },

            _removeIfInvalid: function( event, ui ) {

                // Selected an item, nothing to do
                if ( ui.item ) {
                    return;
                }

                // Search for a match (case-insensitive)
                var value = this.input.val(),
                    valueLowerCase = value.toLowerCase(),
                    valid = false;
                this.element.children( "option" ).each(function() {
                    if ( $( this ).text().toLowerCase() === valueLowerCase ) {
                        this.selected = valid = true;
                        return false;
                    }
                });

                // Found a match, nothing to do
                if ( valid ) {
                    return;
                }

                // Remove invalid value
                this.input
                    .val( "" )
                    .attr( "title", value + " didn't match any item" )
                    .tooltip( "open" );
                this.element.val( "" );
                this._delay(function() {
                    this.input.tooltip( "close" ).attr( "title", "" );
                }, 2500 );
                this.input.autocomplete( "instance" ).term = "";
            },

            _destroy: function() {
                this.wrapper.remove();
                this.element.show();
            }
        });
    })( jQuery );



    $(function() {

        $( "#combobox" ).combobox();

        DrawSearchList();
        GetProviderList();

        $("#search").keyup(function (e) {
            if (e.keyCode == 13) {
                DrawSearchList();
            }
        });

    });


    function DrawSearchList()
    {
        $.get("index.php?c=ChangePrice&a=get_list&search="+$("#search").val().toLowerCase(),function(data){
            $( "#users_list").html(data);
            $("#example").css("width","0");
        });
    }

    function GetProviderList()
    {
        $.get("index.php?c=Provider&a=get_list_data",function(data){
            var obj = JSON.parse(data);
            for(var i=0;i<obj.data.length;i++)
            {
                $("#combobox").append("<option value='"+obj.data[i].id+"'>"+obj.data[i].Name+"</option>");
            }
        });
    }









</script>



<?php

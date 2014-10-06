
<div class="fullscreen">

    <table style="width: 100%" ><tr><td valign="top" style="width: 300px">

                <div id="advanced_search" class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Расширенный поиск</div>
                        <div class="panel-body">

                            <div class="input-group">
                                <span class="input-group-addon">#</span>
                                <input type="text" id="fname" class="form-control" placeholder="Торговое наименование">
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">#</span>
                                <select id="provider" class="form-control">
                                    <option selected value="0">Все</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">#</span>
                                <input type="text" id="price" class="form-control" placeholder="Цена">
                            </div>

                            <div class="input-group">
                                <span class="input-group-addon">#</span>
                                <input type="text" id="rest" class="form-control" placeholder="Остаток">
                            </div>

                            <button type="button" class="btn btn-default btn col-xs-12" onclick="DrawSearchList()">
                                <span class="glyphicon glyphicon-search"></span> Найти
                            </button>

                        </div>
                    </div>
                </div>

    </td><td  valign="top">

                <div id="upload_manage_buttons">

                    <div class="row">

                        <div class="col-xs-10">

                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                                <input type="text" id="serach" class="form-control" placeholder="Начать поиск">
                            </div>

                        </div>

                        <div class="col-xs-2">

                            <button onclick="DrawSearchList()" type="" class="btn btn-default">
                                <span class="glyphicon glyphicon-search"></span> Начать поиск </button>

                        </div>

                    </div>


                </div>

                <div id="search_list"></div>

    </td></tr>
    </table>



</div>



<script type="application/javascript">
    var files;

    $(function() {

        DrawSearchList();
        GetProviderList();

        $("#serach").keyup(function (e) {
            if (e.keyCode == 13) {
                DrawSearchList();
            }
        });

    });


    function DrawSearchList()
    {
        var adv = "&fname="+$("#fname").val()+"&provider="+
            ($("#provider").val()==null ||$("#provider").val()==0 ?"":$("#provider").val())+
            "&price="+$("#price").val()+"&rest="+$("#rest").val();

        $.get("index.php?c=Search&a=get_list&search="+$( "#serach").val()+adv,function(data){
            $( "#search_list").html(data);
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


<table id="example" class="table table-striped table-bordered" cellspacing="0">

    <thead>
    <tr>
        <th>id</th>
        <th style="width: 55px">Код товара в базе МК</th>
        <th>Код товара в базе постащика</th>
        <th>Наименование товара</th>
        <th>Торговое наименование</th>
        <th>Основные характеристики</th>
        <th>Цена в рублях</th>
        <th>Остаток</th>
        <th>Название постащика</th>
    </tr>
    </thead>

</table>

<script src="Helper/dataTables.bootstrap.js"></script>
<script src="Helper/bootstrap-tooltip.js"></script>

<script type="application/javascript">

    var editor; // use a global for the submit and return data rendering in the examples

    $(document).ready(function() {

        var adv = "<?php echo "&search=".$DATA["search"]."&provider=".$DATA["provider"]?>";

        var table = $('#example').DataTable( {
            lengthChange: false,
            "bSort": false,
            "bFilter": false,
            "oLanguage": {
                "sLoadingRecords": "Пожалуйста подождите - загружается...",
                "sProcessing":   "Подождите...",
                "sLengthMenu":   "Показать _MENU_ записей",
                "sZeroRecords":  "Записи отсутствуют.",
                "sInfo":         "Записи с _START_ до _END_ из _TOTAL_ записей",
                "sInfoEmpty":    "Записи с 0 до 0 из 0 записей",
                "sInfoFiltered": "(отфильтровано из _MAX_ записей)",
                "sInfoPostFix":  "",
                "sSearch":       "Поиск:",
                "sUrl":          "",
                "oPaginate": {
                    "sFirst": "Первая",
                    "sPrevious": "<",
                    "sNext": ">",
                    "sLast": "Последняя"
                },
                "oAria": {
                    "sSortAscending":  ": активировать для сортировки столбца по возрастанию",
                    "sSortDescending": ": активировать для сортировки столбцов по убыванию"
                }
            },
            ajax: "index.php?c=ChangePrice&a=get_list_data"+adv,
            columns: [
                { data: "id"},
                //{ data: "Number"},
                { data: null, render: function ( data, type, row ) {
                    return "<p id='"+data.id+"'>"+data.Number+"</p>";
                } },
                { data: "NumberProvider"},
                { data: "Name" },
                { data: "FullName" },
                { data: "BasicCharacteristics" },
                { data: "Price" },
                { data: "Rest" },
                //{ data: "ProviderId" }
                { data: null, render: function ( data, type, row ) {
                    return "<p id='"+data.ProviderId+"'>"+data.Provider+"</p>";
                } }
                /*{ data: null, render: function ( data, type, row ) {
                 return "<button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" data-placement=\"left\" title=\"Tooltip on left\">Tooltip on left</button>";
                 } }*/
            ],
            "columnDefs": [
                {
                    "targets": [ 0 ],
                    "visible": false
                }
            ],
            "order": [[ 2, "desc" ]]
        } );

        var tableTools = new $.fn.dataTable.TableTools( table, {
            sRowSelect: "os"/*,
             aButtons: [
             { sExtends: "editor_create", editor: editor },
             { sExtends: "editor_edit",   editor: editor },
             { sExtends: "editor_remove", editor: editor }
             ]*/
        } );
        //$( tableTools.fnContainer() ).appendTo( '#example_wrapper .col-xs-6:eq(0)' );
    } );

</script>


<?php




<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">

    <thead>
    <tr>
        <th>id</th>
        <th>Файл</th>
        <th>Дата</th>
        <th>Кто</th>
        <th>Поставщик</th>
        <th>Статус</th>
    </tr>
    </thead>

</table>

<script src="Helper/dataTables.bootstrap.js"></script>

<script type="application/javascript">

    var editor; // use a global for the submit and return data rendering in the examples

    $(document).ready(function() {
		//$.fn.dataTableExt.sErrMode = 'throw'
		
        var table = $('#example').DataTable( {
            lengthChange: false,
            "oLanguage": {
                "sLoadingRecords": "Пожалуйста подождите - загружается...",
                "sProcessing":   "Подождите...",
                "sLengthMenu":   "Показать _MENU_ записей",
                "sZeroRecords":  "Записи отсутствуют.",
                "sInfo":         "Записи с _START_ до _END_ из _TOTAL_ записей",
                "sInfoEmpty":    "Записи с 0 до 0 из 0 записей",
                "sInfoFiltered": "(отфильтровано из _MAX_ записей)",
                "sEmptyTable" :  "Ничего не найдено",
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
            ajax: "index.php?c=UploadPrice&a=get_upload_list_data",
            columns: [
                { data: "id"},
                { data: "FileName"},
                { data: "DateTime" },
                { data: "UserId" },
                { data: "ProviderId" },
                //{ data: "Status" }
                { data: null, render: function ( data, type, row ) {
                    if(data.Status.toLowerCase()!="готово")
                        return "<p id='"+data.id+"' class='upd'  status='' >"+data.Status+"</p>";
                    else
                        return data.Status;
                } }
                /*{ data: null, render: function ( data, type, row ) {
                    // Combine the first and last names into a single table field
                    switch(data.Permission)
                    {
                        case "0": return "Администратор"; break;
                        case "1": return "Журналист"; break;
                        case "2": return "Пользователь"; break;
                        default: return "None";
                    }

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
        $( tableTools.fnContainer() ).appendTo( '#example_wrapper .col-xs-6:eq(0)' );
    } );

</script>


<?php




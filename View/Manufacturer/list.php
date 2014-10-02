<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">

    <thead>
    <tr>
        <th>id</th>
        <th>Наименование</th>
        <th>ФИО</th>
        <th>Город</th>
        <th>Адрес</th>
        <th>Телефон</th>
    </tr>
    </thead>

</table>

<script src="Helper/dataTables.bootstrap.js"></script>

<script type="application/javascript">

    var editor; // use a global for the submit and return data rendering in the examples

    $(document).ready(function() {

        var table = $('#example').DataTable( {
            lengthChange: false,
            "oLanguage": {
                "sLoadingRecords": "Пожалуйста подождите - загружается...",
                "sLengthMenu": "Показать _MENU_ записей",
                "sInfo": "Всего _TOTAL_ показано с _START_ по _END_ "
            },
            ajax: "index.php?c=Manufacturer&a=get_list_data",
            columns: [
                { data: "id"},
                { data: "Name"},
                { data: "FullName" },
                { data: "City" },
                { data: "Address" },
                { data: "Phone" }
            ],
            "columnDefs": [
                {
                    "targets": [ 0 ],
                    "visible": false
                }
            ],
            "order": [[ 1, "asc" ]]
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




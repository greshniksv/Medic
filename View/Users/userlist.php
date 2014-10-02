<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">

    <thead>
    <tr>
        <th>id</th>
        <th>Логин</th>
        <th>Имя</th>
        <th>Фамилия</th>
        <th>Доступ</th>
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
            ajax: "index.php?c=Users&a=UserData",
            columns: [
                { data: "id"},
                { data: "Login"},
                { data: "FirstName" },
                { data: "LastName" },
                //{ data: "Permission" }
                { data: null, render: function ( data, type, row ) {
                    // Combine the first and last names into a single table field
                    switch(data.Permission)
                    {
                        case "0": return "Администратор"; break;
                        case "1": return "Журналист"; break;
                        case "2": return "Пользователь"; break;
                        default: return "None";
                    }

                } }
            ],
            "columnDefs": [
                {
                    "targets": [ 0 ],
                    "visible": false
                }
            ]
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




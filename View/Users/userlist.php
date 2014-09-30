<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">

    <thead>
    <tr>
        <th>Логин</th>
        <th>Имя</th>
        <th>Фамилия</th>
        <th>Доступ</th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <th>Логин</th>
        <th>Имя</th>
        <th>Фамилия</th>
        <th>Доступ</th>
    </tr>
    </tfoot>
</table>

<script src="Helper/dataTables.bootstrap.js"></script>

<script type="application/javascript">

    var editor; // use a global for the submit and return data rendering in the examples

    $(document).ready(function() {

        var table = $('#example').DataTable( {
            lengthChange: false,
            "oLanguage": {
                "sInfo": "Всего _TOTAL_ показано (_START_ по _END_)"
            },
            ajax: "index.php?c=Users&a=UserData",
            columns: [
                { data: "Login"},
                { data: "FirstName" },
                { data: "LastName" },
                { data: "Permission" }//,
                //{ data: "salary", render: $.fn.dataTable.render.number( ',', '.', 0, '$' ) }
                /*{ data: null, render: function ( data, type, row ) {
                    // Combine the first and last names into a single table field
                    return data.first_name+' '+data.last_name;
                } },*/
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





<div class="fullscreen">
    <div id="users_form" class="absolute-center">
        <div id="users_list"></div>

        <div id="manage_buttons">
        <button type="button" class="btn btn-default "> <span class="glyphicon glyphicon-plus"></span>  Добавить </button>
        <button type="button" class="btn btn-default "> <span class="glyphicon glyphicon-plus"></span>  Редактировать </button>
        <button type="button" class="btn btn-default "> <span class="glyphicon glyphicon-plus"></span>  Удалить </button>
        </div>
    </div>
</div>




<script type="application/javascript">

    $(function() {
        GoToUsers();


    });

    function GoToUsers()
    {
        $.get("index.php?c=Users&a=UserList",function(data){
            $( "#users_list").html(data);
        });
    }


</script>


<?php



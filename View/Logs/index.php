
<div class="fullscreen">
    <div id="upload_form" class="absolute-center">

        <div id="uploaded_list"></div>

    </div>
</div>



<script type="application/javascript">

    $(function() {
        DrawLogList();
    });


    function DrawLogList()
    {
        $.get("index.php?c=Logs&a=get_log_list",function(data){
            $( "#uploaded_list").html(data);
        });
    }


</script>



<?php

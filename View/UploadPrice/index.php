
<div class="">
    <div id="upload_form" class="">

        <form id="fupload_form">
        <div id="upload_manage_buttons">

            <div class="row">
                    <select id="prov" name="manf" class="form-control">
                        <option selected disabled value="0">Выберите поставщика</option>
                    </select>

                    <button id="upload" type="" class="btn upload-button">
                        <span><img src="images/Upload.png"></span> Загрузить </button>

                    <input id="fileupload" name="Выбирите файл" class="form-control" style="display: none" type="file" >
            </div>

        </div>
        </form>
        <div id="uploaded_list"></div>

    </div>
</div>



<script type="application/javascript">
    var files=null;

    $(function() {
        window.setInterval(function(){ UpdateField(); },1000);

        $("#upload").click(function () {
            if($("#prov").val()==null)
            {
                alert("Вы не выбрали производителя!");
                return;
            }

            $("#fileupload").trigger('click');
        });

        DrawUploadList();
        GetProvList();

        // Add events
        $('input[type=file]').on('change', prepareUpload);

        // Grab the files and set them to our variable
        function prepareUpload(event)
        {
            files = event.target.files;
            $("#fupload_form").trigger('submit');
        }

        $('form').on('submit', uploadFiles);
    });

    function ClearProvider()
    {
        if($("#prov").val()==null)
        {
            alert("Вы не выбрали производителя!");
            return;
        }

        $.get("index.php?c=UploadPrice&a=clear_provider&manuf="+$("#prov").val(),function(data){
            alert(data);
        });
    }


    // Catch the form submit and upload the files
    function uploadFiles(event)
    {
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening

        // START A LOADING SPINNER HERE

        if(files==null) return;
        // Create a formdata object and add the files
        var data = new FormData();
        $.each(files, function(key, value)
        {
            data.append(key, value);
        });
        files=null;

        $.ajax({
            url: 'index.php?c=UploadPrice&a=upload&manuf='+$("#prov").val(),
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(data, textStatus, jqXHR)
            {
                if(typeof data.error === 'undefined')
                {
                    // Success so call function to process the form
                    //submitForm(event, data);
                    DrawUploadList();
                }
                else
                {
                    // Handle errors here
                    alert('Ошибка: ' + data.error);
                    console.log('ERRORS: ' + data.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                // STOP LOADING SPINNER
            }
        });
    }

    function DrawUploadList()
    {
        $.get("index.php?c=UploadPrice&a=get_upload_list",function(data){
            $( "#uploaded_list").html(data);
        });
    }

    function GetProvList()
    {
        $.get("index.php?c=Provider&a=get_list_data",function(data){
            var obj = JSON.parse(data);
            for(var i=0;i<obj.data.length;i++)
            {
                $("#prov").append("<option value='"+obj.data[i].id+"'>"+obj.data[i].Name+"</option>");
            }
        });
    }

    function UpdateField()
    {
        $("p.upd").each(function (a) {

            if ($(this).attr("state") == "done") return;
            var _this = $(this);

            var id = _this.attr("id");

            $.get("index.php?c=UploadPrice&a=get_status&id="+id, function (data) {
                var res = JSON.parse(data);
                $("#"+id).html(res.status);
                if(res.status.toLowerCase()=="готово")
                {
                    _this.attr("state","done");
                }
            });

        });
    }



</script>



<?php

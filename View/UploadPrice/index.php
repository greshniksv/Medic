
<div class="fullscreen">
    <div id="upload_form" class="absolute-center">

        <form id="fupload_form">
        <div id="upload_manage_buttons">

            <div class="row">

                <div class="col-md-10">
                    <select id="manuf" name="manf" class="form-control">
                        <option selected disabled value="0">Выберите поставщика</option>
                    </select>
                </div>

                <div class="col-md-2">

                    <button id="upload" type="" class="btn btn-default">
                        <span class="glyphicon glyphicon-upload"></span> Загрузить </button>

                <input id="fileupload" name="Выбирите файл" class="form-control" style="display: none" type="file" >


                </div>
            </div>


        </div>
        </form>
        <div id="uploaded_list"></div>

    </div>
</div>



<script type="application/javascript">
    var files;

    $(function() {

        $("#upload").click(function () {
            if($("#manuf").val()==null)
            {
                alert("Вы не выбрали производителя!");
                return;
            }

            $("#fileupload").trigger('click');
        });

        DrawUploadList();
        GetManufList();

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

    // Catch the form submit and upload the files
    function uploadFiles(event)
    {
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening

        // START A LOADING SPINNER HERE

        // Create a formdata object and add the files
        var data = new FormData();
        $.each(files, function(key, value)
        {
            data.append(key, value);
        });
        files=null;

        $.ajax({
            url: 'index.php?c=UploadPrice&a=upload&manuf='+$("#manuf").val(),
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(data, textStatus, jqXHR)
            {
                console.log("1");
                if(typeof data.error === 'undefined')
                {
                    // Success so call function to process the form
                    //submitForm(event, data);
                    DrawUploadList();
                }
                else
                {
                    // Handle errors here
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

    function GetManufList()
    {
        $.get("index.php?c=Manufacturer&a=get_list_data",function(data){
            var obj = JSON.parse(data);
            for(var i=0;i<obj.data.length;i++)
            {
                $("#manuf").append("<option value='"+obj.data[i].id+"'>"+obj.data[i].Name+"</option>");
            }
        });
    }


</script>



<?php

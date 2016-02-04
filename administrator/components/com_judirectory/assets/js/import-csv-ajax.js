jQuery(document).ready(function ($) {
    function importImagesAjax(start) {
        $.ajax({
            type    : "GET",
            data    : {start: start},
            dataType: 'json',
            url     : "index.php?option=com_judirectory&task=csvprocess.importProcessing",
            success : function (response) {
                var processed = parseInt(response['processed']) + parseInt(start);
                var percent = Math.floor((processed / response['total']) * 100);

                $("#processed").show();
                $("#total").show();
                $("#processed").html(processed);
                $("#total").html(response['total']);

                if (percent >= 100) {
                    $("#process_state").html(Joomla.JText._('COM_JUDIRECTORY_IMPORT_CSV_FINISHED', 'Finished'));
                    $("#processed").html(response['total']);
                    $("#bar").width(100 + '%');

                    //display message
                    if (typeof response["message"] != "undefined") {
                        $("#import_messages").html(response["message"]);
                    }

                    return false;
                }
                else {
                    $("#bar").width(percent + '%');

                    importImagesAjax(processed);
                }
            }
        });
    }

    importImagesAjax(0);
});

(function ($) {
    $(document).ready(function () {
        $("#addr")
            .geocomplete({details: "#frm_addr"})
            .bind("geocode:result", function () {
                $("#frm_addr #add").removeAttr('disabled');
                // $('#frm_addr').submit();
            });

        var addrChange = function () {
            $("#frm_addr #add").attr('disabled', 'disabled');
            $("#frm_addr input[name='lat']").val('');
        };

        var markersBind = function () {
            $(".delete_marker").click(function () {
                $(this).attr('disabled', 'disabled');
                var id = $(this).data('id');
                var user_id = $(this).data('user_id');
                var el = $(this).closest('li');
                window.MVC_Post('Maps', 'deleteMarker', {id: id, user_id: user_id}, function (data) {
                    if (!data) {
                        el.remove();
                    } else {
                        alert(data);
                    }
                });
            });
        };
        markersBind();

        $("#frm_addr #addr").change(addrChange).keypress(addrChange);
        $("#frm_addr").submit(function () {
            if (!$("#frm_addr input[name='lat']").val()) {
                $("#frm_addr #addr").trigger("geocode");
                return false;
            }

            var form_data = $('#frm_addr').serializeArray();

            $('#frm_addr #add').attr('disabled', 'disabled');

            window.MVC_Post('Maps', 'addMarker', form_data, function (data) {
                if (data) {
                    $(".edit_markers").html(data);
                    markersBind();
                    $("#frm_addr #addr").val('');
                    addrChange();
                } else {
                    alert('error');
                }
            });

            return false;
        });


    });
})(jQuery);
(function ($) {
    $(document).ready(function () {

        // $("a[href='#tab4']").click();

        $('#subscriptions .show_confirm').click(function () {
            var key = $(this).closest('tr').data('key');
           // $('.cancel_confirm[data-key="' + key + '"]').show();
            $('.cancel_subscription_pop-up[data-key="' + key + '"]').show();
            
        });

        $('#subscriptions .do_not_cancel').click(function () {
            $(this).closest('tr').hide();
        });

        $('#subscriptions .cancel_subscription').click(function () {
            var tr = $(this).closest('tr');
            var key = tr.data('key');
            window.MVC_Post('Portal', 'cancelSubscription', {
                key: key
            }, function (data) {
                if (data) {
                    alert(data);
                } else {
                    $("#subscriptions tr[data-key='" + key + "']").remove();
                }
            });

            return false;
        });

    });
})(jQuery);

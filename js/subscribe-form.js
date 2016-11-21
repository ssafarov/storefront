function newsletterSubscribe(el, $) {
    var newsletterSubscribe = ".newsletter-subscribe",
        errorResponse = "#mce-error-response",
        successResponse = "#mce-success-response",
        formName = $(el).attr('data-form-submit'),
        self = el;

    $(self).attr('disabled', 'disabled');
    $(self).addClass('inActive');
    $.post(
        ajaxurl,
        {
            'action': 'woochimp_subscribe_shortcode',
            'data': jQuery('#'+formName).serialize()
        },
        function (response) {
            var result = jQuery.parseJSON(response);
            $(self).parent().parent().parent().find(errorResponse).text('');
            $(self).parent().parent().parent().find(successResponse).text('');

            if (result['error'] == 1) {
                // Display warning
                $(self).parent().parent().parent().find(errorResponse).text('Please check valid email. '+result['message']);
                $(self).parent().parent().parent().find(errorResponse).show();
                $(self).parent().parent().parent().find(errorResponse).delay(500).fadeIn(500);
                $(self).removeAttr('disabled');
                $(self).removeClass('inActive');
            }
            else {
                // Display success message
                $(self).parent().parent().parent().find(successResponse).text(result['message']);
                $(self).parent().parent().parent().find(successResponse).show();
                $(self).parent().parent().parent().find(successResponse).delay(500).fadeIn(500);
                $(self).removeAttr('disabled');
                $(self).removeClass('inActive');
            }

        });
    return false;
}
/* <input data-validation="email_fix" type="email" ... */
(function ($) {
    $(document).ready(function () {
        /*
         * Validate email_fix
         */
        $.formUtils.addValidator({
            name: 'email_fix',
            validatorFunction: function (email) {

                var emailParts = email.toLowerCase().split('@');
                if (emailParts.length == 2) {
                    return $.formUtils.validators.validate_domain_fix.validatorFunction(emailParts[1]) && !(/[^\w\+\.\-]/.test(emailParts[0])) && emailParts[0].length > 0;
                }

                return false;
            },
            errorMessage: '',
            errorMessageKey: 'badEmail'
        });


        /*
         * Validate domain name
         */
        $.formUtils.addValidator({
            name: 'domain_fix',
            validatorFunction: function (val) {
                return val.length > 0 &&
                    val.length <= 253 && // Including sub domains
                    !(/[^a-zA-Z0-9]/.test(val.slice(-2))) &&
                    /* !(/[^a-zA-Z]/.test(val.substr(0, 1)))  && */
                    !(/[^a-zA-Z0-9\.\-]/.test(val)) &&
                    val.split('..').length == 1 &&
                    val.split('.').length > 1;
            },
            errorMessage: '',
            errorMessageKey: 'badDomain'
        });
    });
})(jQuery);
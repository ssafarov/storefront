(function ($) {
    $(document).ready(function () {
        $('#devices .del').click(function () {
            var self = this;
            window.MVC_Post('Portal', 'deleteLicenseCodeActivity', {
                productid: $(this).data('product'),
                code: $(this).data('code'),
                computerid: $(this).data('computer')
            }, function (data) {
                if (data) {
                    alert(data);
                } else {
                    $(self).closest('.device-item').remove();
                }
            });

            return false;
        });

        var adding_serial = false;

        $('.btn-add-serial').closest('form').submit(function (e) {
            var $this = $('.btn-add-serial'),
                $form = $this.closest('form'),
                $serial = $form.find('input[type=text]'),
                serial = $serial.val().trim(),
                errorMessage = 'Invalid serial number';

            if (serial) {
                $this.attr('disabled', 'disabled');
                $serial.parent().removeClass('has-error').find('span.help-block').hide().text(errorMessage);

                $.post($form.attr('action'), {serial: serial}, function (r) {
                    if (r.success) {
                        $('ul.license').append(
                            '<li>' +
                                serial + '&nbsp;' +
                                '<a title="Unassign" href="#" class="remove-serial tooltip_off" data-serial="' + serial + '"><i class="icon-error"></i></a>' +
                            '</li>');
                        $('.no-serial').remove();
                        $form.find('input[type=text]').val('');

                        if (r.downloads) {
                            $('div.software-downloads').html(r.downloads);
                        }
                    } else {
                        $serial.parent().addClass('has-error').find('span.help-block').show().text(r.message);
                    }

                    $this.removeAttr('disabled');
                });
            } else {
                $serial.parent().addClass('has-error').find('span.help-block').show().text(errorMessage);
            }

            e.preventDefault();
        });

        $('.btn-add-serial').closest('form').find('input[type=text]').change(function () {
            $(this).parent().removeClass('has-error').find('span.help-block').hide();
        });

        $('ul.license').on('click', '.remove-serial', function () {
            var $this = $(this),
                serial = $this.data('serial'),
                url = $('ul.license').data('remove-url');

            if (confirm('Are you sure you want to remove this serial number from your account?')) {
                $.post(url, {serial: serial}, function (r) {
                    if (r.success) {
                        $this.closest('li').remove();

                        if ($('ul.license > li').length == 0) {
                            $('div.software-downloads').html('To gain access to Fuel3D Studio software, you must register your SCANIFY serial number above');
                        }
                    } else {
                        alert('There was an error removing your serial number');
                    }
                });
            }

            return false;
        });


        $(".software-downloads a").click(function () {
            var href = $(this).data('href');
            if (!href) {
                href = $(this).attr('href');
            }

            window.MVC_Post('Portal', 'downloadSaveData', {
                href: href,
                user_id: window.user_id
            }, function (data) {
                if (data) {
                    alert(data);
                } else {
                    window.location.href = href;
                }
            });

            return false;
        });

        $("#upgrade_button").prop('disabled', true);

        $("#upgrade_selectbox").change(function () {
            if ($(this).val()) {
                $("#upgrade_button").prop('disabled', false);
            } else {
                $("#upgrade_button").prop('disabled', true);
            }
        });

    });
})(jQuery);
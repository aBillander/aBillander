$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();

    // registration form steps start
    if ($('#business_register_form').length) {
        var form = $('#business_register_form').show();
        form.steps({
            headerTag: 'h3',
            bodyTag: 'fieldset',
            transitionEffect: 'slideLeft',
            labels: {
                finish: LANG.register,
                next: LANG.next,
                previous: LANG.previous,
            },
            onStepChanging: function(event, currentIndex, newIndex) {
                // Allways allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find('.body:eq(' + newIndex + ') label.error').remove();
                    form.find('.body:eq(' + newIndex + ') .error').removeClass('error');
                }
                form.validate().settings.ignore = ':disabled,:hidden';
                return form.valid();
            },
            onFinishing: function(event, currentIndex) {
                form.validate().settings.ignore = ':disabled';
                return form.valid();
            },
            onFinished: function(event, currentIndex) {
                form.submit();
            },
        });
    }
    // registration form steps end

    //Date picker
    $('.start-date-picker').datepicker({
        autoclose: true,
        endDate: 'today',
    });

    $('form#business_register_form').validate({
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else if (element.hasClass('input-icheck') && element.parent().hasClass('icheckbox_square-blue')) {
                error.insertAfter(element.parent().parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            name: 'required',
            email: {
                email: true,
                remote: {
                    url: '/business/register/check-email',
                    type: 'post',
                    data: {
                        email: function() {
                            return $('#email').val();
                        },
                    },
                },
            },
            password: {
                required: true,
                minlength: 5,
            },
            confirm_password: {
                equalTo: '#password',
            },
            username: {
                required: true,
                minlength: 4,
                remote: {
                    url: '/business/register/check-username',
                    type: 'post',
                    data: {
                        username: function() {
                            return $('#username').val();
                        },
                    },
                },
            },
            website: {
                url: true,
            },
        },
        messages: {
            name: LANG.specify_business_name,
            password: {
                minlength: LANG.password_min_length,
            },
            confirm_password: {
                equalTo: LANG.password_mismatch,
            },
            username: {
                remote: LANG.invalid_username,
            },
            email: {
                remote: LANG.email_taken,
            },
        },
    });

    $('#business_logo').fileinput({
        showUpload: false,
        showPreview: false,
        browseLabel: LANG.file_browse_label,
        removeLabel: LANG.remove,
    });
});

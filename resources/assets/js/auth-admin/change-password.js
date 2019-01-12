var ChangePassword = {
    CURRENT_PASSWORD: "current_password",

    init: function() {
        ChangePassword.initValidation();
    },

    initValidation: function() {
        $('#change-password-form').validate({
            errorElement: "span",
            errorClass: "help-block",

            // error
            highlight: function(element, errorClass, successClass) {
                var feedback = $(element).closest('.form-group').find('.form-control-feedback');
                var form_group = $(element).closest('.form-group');

                if (form_group.hasClass('has-success')) {
                    form_group.removeClass('has-success');
                }

                form_group.addClass('has-error');

                if (feedback.length === 0) {
                    $(element).after('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
                } else {
                    feedback.addClass('glyphicon-remove');
                }
            },

            // success
            unhighlight: function(element, errorClass, successClass) {
                var feedback = $(element).closest('.form-group').find('.form-control-feedback');
                var form_group = $(element).closest('.form-group');

                if ($(element).attr('name') === ChangePassword.CURRENT_PASSWORD) {
                    if (feedback.length > 0) {
                        if (feedback.hasClass('glyphicon-remove')) {
                            feedback.removeClass('glyphicon-remove');
                        }
                    }

                    if (form_group.hasClass('has-error')) {
                        form_group.removeClass('has-error');
                    }
                } else {
                    if (form_group.hasClass('has-error')) {
                        form_group.removeClass('has-error');
                    }

                    form_group.addClass('has-success');

                    if (feedback.length === 0) {
                        $(element).after('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');
                    } else if (feedback.hasClass('glyphicon-remove')) {
                        feedback.removeClass('glyphicon-remove').addClass('glyphicon-ok');
                    }
                }
            },

            rules: {
                current_password: {
                    required: true
                },
                new_password: {
                    required: true,
                    password_strength: true
                },
                confirm_new_password: {
                    required: true,
                    equalTo: '#change-password-form :input[name="new_password"]'
                }
            },

            messages: {
                confirm_new_password: {
                    equalTo: "Password and confirm password do not match"
                }
            }
        });
    }
};

$(document).ready(ChangePassword.init);

var ChangePassword = {
    init: function() {
        ChangePassword.initValidation();
    },

    initValidation: function() {
        $('#change-password-form').validate({
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
                } else if (feedback.hasClass('glyphicon-ok')) {
                    feedback.removeClass('glyphicon-ok');
                }

                feedback.addClass('glyphicon-remove');
            },

            // success
            unhighlight: function(element, errorClass, successClass) {
                var feedback = $(element).closest('.form-group').find('.form-control-feedback');
                var form_group = $(element).closest('.form-group');

                if (form_group.hasClass('has-error')) {
                    form_group.removeClass('has-error');
                }

                if (feedback.length === 0) {
                    $(element).after('<span class="glyphicon form-control-feedback"></span>');
                    feedback.removeClass('glyphicon-remove');
                } else if (feedback.hasClass('glyphicon-remove')) {
                    feedback.removeClass('glyphicon-remove');
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
            }
        });
    }
};

$(document).ready(ChangePassword.init);

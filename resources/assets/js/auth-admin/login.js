var Login = {
    init: function() {
        Login.initValidation();
    },

    initValidation: function() {
        $('#login-form').validate({
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

                if (feedback.length > 0) {
                    if (feedback.hasClass('glyphicon-remove')) {
                        feedback.removeClass('glyphicon-remove');
                    }
                }

                if (form_group.hasClass('has-error')) {
                    form_group.removeClass('has-error');
                }
            },

            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                }
            }
        });
    }
};

$(document).ready(Login.init);

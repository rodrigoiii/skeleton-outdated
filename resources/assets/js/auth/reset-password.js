var ResetPassword = {
    init: function() {
        ResetPassword.initValidation();
    },

    initValidation: function() {
        $('#reset-password-form').validate({
            rules: {
                new_password: {
                    required: true,
                    password_strength: true
                },
                confirm_new_password: {
                    required: true,
                    equalTo: '#reset-password-form :input[name="new_password"]'
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

$(document).ready(ResetPassword.init);

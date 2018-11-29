var ChangePassword = {
    init: function() {
        ChangePassword.initValidation();
    },

    initValidation: function() {
        $('#change-password-form').validate({
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

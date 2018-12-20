var ForgotPassword = {
    init: function() {
        ForgotPassword.initValidation();
    },

    initValidation: function() {
        $('#forgot-password-form').validate({
            rules: {
                email: {
                    required: true,
                    email: true
                }
            }
        });
    }
};

$(document).ready(ForgotPassword.init);

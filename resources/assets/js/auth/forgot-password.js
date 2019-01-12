var ForgotPassword = {
    init: function() {
        ForgotPassword.initValidation();
    },

    initValidation: function() {
        $('#forgot-password-form').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                    remote: "/api/jv/email-exist"
                }
            },

            messages: {
                email: {
                    remote: "Email is not exist!"
                }
            }
        });
    }
};

$(document).ready(ForgotPassword.init);

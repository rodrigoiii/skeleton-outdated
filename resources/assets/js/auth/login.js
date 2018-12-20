var Login = {
    init: function() {
        Login.initValidation();
    },

    initValidation: function() {
        $('#login-form').validate({
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

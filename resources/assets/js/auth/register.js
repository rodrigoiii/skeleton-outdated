require("./../app");

var Register = {
    init: function() {
        $('#register-form').validate({
            rules: {
                first_name: {
                    required: true,
                    alpha: true
                },
                last_name: {
                    required: true,
                    alpha: true
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    password_strength: true
                },
                confirm_password: {
                    required: true,
                    equalTo: '#register-form :input[name="password"]'
                }
            }
        });
    }
};

$(document).ready(Register.init);

require("./../app");

var Register = {
    init: function() {
        $('#register-form').validate({
            rules: {
                picture_file: {
                    required: true,
                    accept: "image/gif,image/jpeg,image/png",
                    file_size: {
                        max_size: 5000000, // 5mb
                    }
                },
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

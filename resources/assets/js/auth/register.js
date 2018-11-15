require("./../app");

var Register = {
    init: function() {
        $.validator.addMethod("alpha", function(value, element) {
            console.log("alpha", this.min_length);
            return this.optional(element) || /^[a-zA-Z]+$/i.test(value);
        }, "Please enter only letters.");

        $.validator.addMethod("password_strength", function(value, element, params) {
            var min_length = params.min_length || 8;
            var lower = params.lower || 0;
            var upper = params.upper || 0;
            var number = params.number || 0;
            var special_char = params.special_char || 0;

            return this.optional(element) || (
                value.length >= this.min_length && // password length
                (value.match(/[a-z]/g) !== null  ? value.match(/[a-z]/g).length : 0) >= lower && // lower case
                (value.match(/[A-Z]/g) !== null  ? value.match(/[A-Z]/g).length : 0) >= upper && // upper case
                (value.match(/[0-9]/g) !== null  ? value.match(/[0-9]/g).length : 0) >= number && // number
                (value.match(/[^a-zA-Z0-9\s]/g) !== null  ? value.match(/[^a-zA-Z0-9\s]/g).length : 0) >= special_char // special characters
            );
        }, function(params, element) {
            var min_length = params.min_length || 8;
            var lower = params.lower || 0;
            var upper = params.upper || 0;
            var number = params.number || 0;
            var special_char = params.special_char || 0;

            var value = $(element).val();
            var error_message = "";

            if (value.length < min_length) {
                error_message = "Password must be at least "+min_length+" character(s).";
            } else if ((value.match(/[a-z]/g) !== null  ? value.match(/[a-z]/g).length : 0) < lower) {
                error_message = "Password must have "+lower+" lower case.";
            } else if ((value.match(/[A-Z]/g) !== null  ? value.match(/[A-Z]/g).length : 0) < upper) {
                error_message = "Password must have "+upper+" upper case.";
            } else if ((value.match(/[0-9]/g) !== null  ? value.match(/[0-9]/g).length : 0) < number) {
                error_message = "Password must have "+number+" number(s).";
            } else if ((value.match(/[^a-zA-Z0-9\s]/g) !== null  ? value.match(/[^a-zA-Z0-9\s]/g).length : 0) < special_char) {
                error_message = "Password must have "+special_char+" special character(s).";
            }

            return error_message;
        });

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

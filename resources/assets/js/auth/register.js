var Register = {
    init: function() {
        Register.initValidation();

        // ok icon
        // incorrect icon

        // allow ok in field
        // allow incorrect in field
        // allow ok only in field
        // allow incorrect only in field
        // allow ok for all // default
        // allow incorrect for all // default
    },

    initValidation: function() {
        $('#register-form').validate({
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
                } else if (feedback.hasClass('glyphicon-ok')) {
                    feedback.removeClass('glyphicon-ok').addClass('glyphicon-remove');
                }
            },

            // success
            unhighlight: function(element, errorClass, successClass) {
                var feedback = $(element).closest('.form-group').find('.form-control-feedback');
                var form_group = $(element).closest('.form-group');

                if (form_group.hasClass('has-error')) {
                    form_group.removeClass('has-error');
                }

                form_group.addClass('has-success');

                if (feedback.length === 0) {
                    $(element).after('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');
                } else if (feedback.hasClass('glyphicon-remove')) {
                    feedback.removeClass('glyphicon-remove').addClass('glyphicon-ok');
                }
            },

            rules: {
                picture: {
                    required: true,
                    accept: "image/gif,image/jpeg,image/png",
                    file_size: {
                        max_size: 5000000, // 5mb
                    }
                },
                first_name: {
                    required: true,
                    regex: /^[a-zA-Z\s]+$/i
                },
                last_name: {
                    required: true,
                    regex: /^[a-zA-Z\s]+$/i
                },
                email: {
                    required: true,
                    email: true,
                    remote: "/api/jv/email-exist?invert"
                },
                password: {
                    required: true,
                    password_strength: true
                },
                confirm_password: {
                    required: true,
                    equalTo: '#register-form :input[name="password"]'
                }
            },

            messages: {
                first_name: {
                    regex: "Please enter only letters."
                },
                last_name: {
                    regex: "Please enter only letters."
                },
                email: {
                    remote: "Email is already taken."
                },
                confirm_password: {
                    equalTo: "Password and confirm password do not match"
                }
            }
        });
    }
};

$(document).ready(Register.init);

function quickLoadImage(input, output_el) {
    var reader = new FileReader();

    reader.onload = function(e) {
        output_el.setAttribute('src', e.target.result);
    };

    if (typeof(input.files) !== "undefined") {
        if (input.files.length < 1) {
            output_el.setAttribute('src', "");
        } else if (input.files.length === 1) {
            if (/^image\/./g.test(input.files[0].type)) { // valid image
                reader.readAsDataURL(input.files[0]);
            } else {
                output_el.setAttribute('src', "");
                console.error("Invalid file type! It must be image.");
            }
        } else {
            output_el.setAttribute('src', "");
            console.error("File must be one only.");
        }
    }
}

window.quickLoadImage = quickLoadImage;

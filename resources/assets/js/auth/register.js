var JvBs3 = require("./helpers/JvBs3");

var Register = {
    init: function() {
        Register.initValidation();

        // ok icon
        // incorrect icon

        // allow ok only in [field]
        // allow ok for all // default

        // allow incorrect only in field
        // allow incorrect for all // default
    },

    initValidation: function() {
        JvBs3.unhighlight_only_fields = ["first_name"];

        $('#register-form').validate({
            errorElement: "span",
            errorClass: "help-block",

            // error
            highlight: JvBs3.highlight_all,

            // ok
            unhighlight: JvBs3.unhighlight_fields,

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

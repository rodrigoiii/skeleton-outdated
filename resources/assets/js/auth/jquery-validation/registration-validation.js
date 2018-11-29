$('#register-form').validate({
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
            alpha_including_space: true
        },
        last_name: {
            required: true,
            alpha_including_space: true
        },
        email: {
            required: true,
            email: true,
            remote: {
                url: "",
                type: "POST",
                data: {
                    email: function() {
                        return $('#register-form :input[name="email"]').val();
                    }
                }
            }
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

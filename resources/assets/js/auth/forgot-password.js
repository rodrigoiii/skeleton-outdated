require("./../app");

var Register = {
    init: function() {
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

$(document).ready(Register.init);

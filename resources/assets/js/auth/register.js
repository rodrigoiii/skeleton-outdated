require("./../app");

var Register = {
    init: function() {
        // $('#register-form').validate({
        //     rules: {
        //         first_name: {
        //             required: true
        //         },
        //         last_name: {
        //             required: true
        //         },
        //         email: {
        //             required: true
        //         },
        //         password: {
        //             required: true
        //         },
        //         confirm_password: {
        //             required: true
        //         }
        //     }
        // });

        // 'first_name' => v::notEmpty()->stringType(),
        // 'last_name' => v::notEmpty()->stringType(),
        // 'email' => v::notEmpty()->email(),
        // 'password' => v::notEmpty()->passwordStrength(),
        // 'confirm_password' => v::notEmpty()->passwordMatch($this->request->getParam('password'))
    }
};

$(document).ready(Register.init);

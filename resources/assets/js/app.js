var Bs3Defaults = require("./BuiltIn/Bs3Defaults");
var UserRequest = require("./Requests/UserRequest");

var App = {
    init: function() {
        $.validator.setDefaults(Bs3Defaults);
        $('form').validate(UserRequest.toJSON());
    }
};

$(document).ready(App.init);

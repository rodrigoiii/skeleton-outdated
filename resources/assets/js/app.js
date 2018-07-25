var Default = require("./Requests/Default");
var UserRequest = require("./Requests/UserRequest");

var App = {
    init: function() {
        $.validator.setDefaults(Default);
        $('form').validate(UserRequest.toJSON());
    }
};

$(document).ready(App.init);

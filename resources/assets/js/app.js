// include jquery if it's not define
if (typeof(jQuery) === "undefined") {
    window.jQuery = require("jquery");
    window.$ = window.jQuery;
}

var App = {
    init: function() {
        require("bootstrap-sass/assets/javascripts/bootstrap/transition");
        require("bootstrap-sass/assets/javascripts/bootstrap/collapse");
        require("bootstrap-sass/assets/javascripts/bootstrap/dropdown");
        require("bootstrap-sass/assets/javascripts/bootstrap/alert");
    }
};

$(document).ready(App.init);

// include jquery if it's not define
if (typeof(jQuery) === "undefined") {
    window.jQuery = require("jquery");
}

require("bootstrap/js/transition");
require("bootstrap/js/collapse");
require("bootstrap/js/dropdown");

function readURL(input, output_el) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.readAsDataURL(input.files[0]);

        reader.onload = function(e) {
            output_el.setAttribute('src', e.target.result);
        };
    } else {
        output_el.setAttribute('src', "");
    }
}

window.readURL = readURL;

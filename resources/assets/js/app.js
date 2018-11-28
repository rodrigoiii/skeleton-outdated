// include jquery if it's not define
if (typeof(jQuery) === "undefined") {
    window.jQuery = require("jquery");
}

require("bootstrap/js/transition");
require("bootstrap/js/collapse");
require("bootstrap/js/dropdown");
require("bootstrap/js/alert");

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

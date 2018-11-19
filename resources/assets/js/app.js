// include jquery if it's not define
if (typeof(jQuery) === "undefined") {
    window.jQuery = require("jquery");
}

require("bootstrap/js/transition");
require("bootstrap/js/collapse");
require("bootstrap/js/dropdown");

function quickImageLoad(input, output_el) {
    var reader = new FileReader();

    if (typeof(input.files) !== "undefined") {
        if (input.files.length === 1) { // single file
            if (/^image\/./g.test(input.files[0].type)) { // valid image

            } else {
                output_el.setAttribute('src', "");
            }

            // if (input.files && input.files[0]) {
            //     reader.readAsDataURL(input.files[0]);

            //     reader.onload = function(e) {
            //         output_el.setAttribute('src', e.target.result);
            //     };
            // } else {
            //     output_el.setAttribute('src', "");
            // }
        } else if (input.files.length > 1) { // multiple files

        }
    }


    return null;
}

window.quickImageLoad = quickImageLoad;

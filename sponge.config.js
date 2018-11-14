var fs = require("fs-extra");

module.exports = {
    debug: false,

    sass: {
        src: "resources/assets/sass/**/*.scss",
        dest: "public/css"
    },

    scripts: {
        watch_only: "resources/assets/js/**/*.js",
        src: [
            "resources/assets/js/auth/**/*.js",
            "resources/assets/js/BuiltIn/lib/**/*.js"
        ],
        dest: "public/js",
    },

    build_views: {
        src: "resources/views/**/*.twig",
        dest: "resources/dist-views",
        useref_options: {
            searchPath: "public"
        }
    },

    build_images: {
        src: [
            "public/**/*.jpg",
            "public/**/*.png",
            "public/**/*.gif",
        ],
        dest: "public/dist/img",
        use_flatten: false
    },

    build_fonts: {
        src: [
            "public/**/*.eot",
            "public/**/*.svg",
            "public/**/*.ttf",
            "public/**/*.woff",
            "public/**/*.woff2",
            "public/**/*.otf"
        ],
        dest: "public/dist/fonts",
        use_flatten: true
    },

    unbuild_dir: ["public/dist", "resources/dist-views"],

    build_callback: function() {
        fs.copy("resources/dist-views/dist", "public/dist", function(err) {
            if (err) return console.error(err);

            fs.remove("resources/dist-views/dist");
        });
    },

    unbuild_callback: function() {
        console.log("unbuild callback");
    }
};

var fs = require("fs-extra");

module.exports = {
    debug: false,

    sass: {
        src: "resources/assets/sass/**/*.scss",
        dest: "public/css",
        notify: false
    },

    scripts: {
        entries: {
            "auth": "resources/assets/js/auth/auth.js",
            "auth/register": "resources/assets/js/auth/register.js",
            "auth/login": "resources/assets/js/auth/login.js",
            "auth/change-password": "resources/assets/js/auth/change-password.js",
            "auth/account-setting": "resources/assets/js/auth/account-setting.js",
            "auth/forgot-password": "resources/assets/js/auth/forgot-password.js",
            "auth/reset-password": "resources/assets/js/auth/reset-password.js",

            "jquery-validation/add-methods": "resources/assets/js/auth/jquery-validation/add-methods.js"
        },
        dest: "public/js",
        options: {
            base: "resources/assets/js"
        },
        notify: false
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

    build: {
        callback: function() {
            fs.copy("resources/dist-views/dist", "public/dist", function(err) {
                if (err) return console.error(err);

                fs.remove("resources/dist-views/dist");
            });
        }
    },

    unbuild: {
        dir: ["public/dist", "resources/dist-views"],
        callback: function() {
            console.log("unbuild callback");
        }
    }
};

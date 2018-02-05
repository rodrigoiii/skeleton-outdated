// these are relative at the location of gulpfile.js.
module.exports = {
    tasks_dir: "./resources/assets/gulp/tasks",

    sass: {
        command: "sass",
        watch_command: "sass:watch",
        dir: "resources/assets/sass",
        dest: "public/css",
        sources: [
            "app.scss",
            "a.scss"
        ]
    },

    scripts: {
        command: "scripts",
        watch_command: "scripts:watch",
        dir: "resources/assets/js",
        dest: "public/js",
        sources: [
            "test.js",
            "built-in/csrf-helper.js"
        ]
    },

    build: {
        css_js_sources: ["resources/views/**/*.twig"],
        css_js_search_path: "public",

        img_sources: [],
        img_dest: "",

        font_sources: [],
        font_dest: "",

        dist: "public/dist",
    }
};
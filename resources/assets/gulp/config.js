// these are relative at the location of gulpfile.js.
module.exports = {
    tasks_dir: "./resources/assets/gulp/tasks",

    sass: {
        command: "sass",
        watch_command: "sass-watch",
        dir: "resources/assets/sass",
        dest: "public/css",
        sources: [
            "app.scss"
        ]
    },

    scripts: {
        command: "scripts",
        watch_command: "scripts-watch",
        dir: "resources/assets/js",
        dest: "public/js",
        sources: [
            "test.js",
            "built-in/csrf-helper.js"
        ]
    }
};
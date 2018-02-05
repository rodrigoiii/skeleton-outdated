// these are relative at the location of gulpfile.js.
module.exports = {
    tasks_dir: "./resources/assets/gulp/tasks",
    default_task: "scripts",

    sass: {
        dir: "resources/assets/sass",
        dest: "public/css",
        sources: [
            "app.scss"
        ]
    },

    scripts: {
        dir: "resources/assets/js",
        dest: "public/js",
        sources: [
            "test.js",
            "built-in/csrf-helper.js",
        ]
    }
};
var gulp = require("gulp"),
    plugins = require("gulp-load-plugins")({
        DEBUG: true,
        rename: {
            'gulp-merge-media-queries': "mmq"
        }
    }),
    config = require("./resources/assets/gulp/config");

function getTask(task) {
    return require(config.tasks_dir + "/" + task)(gulp, plugins, config);
}

gulp.task(config.sass.command, getTask("sass"));
gulp.task(config.scripts.command, getTask("scripts"));
gulp.task(config.sass.watch_command, [config.sass.command], getTask('sass-watch'));
gulp.task(config.scripts.watch_command, [config.scripts.command], getTask('scripts-watch'));
gulp.task("watch", [config.sass.watch_command, config.scripts.watch_command], function () {});
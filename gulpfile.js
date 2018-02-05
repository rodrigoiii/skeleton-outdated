var gulp = require("gulp"),
    plugins = require("gulp-load-plugins")({
        rename: {
            'gulp-merge-media-queries': "mmq"
        }
    }),
    config = require("./resources/assets/gulp/config");

function getTask(task) {
    return require(config.tasks_dir + "/" + task)(gulp, plugins, config);
}

gulp.task('sass', getTask("sass"));
gulp.task('scripts', getTask("scripts"));
gulp.task('default', getTask(config.default_task));
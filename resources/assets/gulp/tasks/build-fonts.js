module.exports = function (gulp, plugins, config) {
    return function () {
        var sources = [];
        for (var i in config.build.fonts.sources) {
            sources[i] = config.build.fonts.dir + "/" + config.build.fonts.sources[i];
        }

        return gulp.src(sources)
            .pipe(plugins.flatten())
            .pipe(gulp.dest(config.build.dist + (config.build.fonts.dest !== "" ? "/" + config.build.fonts.dest : "")));
    };
};
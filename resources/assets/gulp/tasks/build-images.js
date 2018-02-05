module.exports = function (gulp, plugins, config) {
    var sources = [];
    for (var i in config.build.images.sources) {
        sources[i] = config.build.images.dir + "/" + config.build.images.sources[i];
    }

    return function () {
        return gulp.src(sources)
                .pipe(plugins.cache(plugins.imagemin({
                    interlaced: true
                })))
                .pipe(gulp.dest(config.build.dist + (config.build.images.dest !== "" ? "/" + config.build.images.dest : "")));
    };
};
module.exports = function (gulp, plugins, config) {
    return function () {
        var sources = [];
        for (var i in config.scripts.sources) {
            sources[i] = config.scripts.dir + "/" + config.scripts.sources[i];
        }

        return gulp.src(sources, {base: config.scripts.dir})
                .pipe(plugins.plumber({
                    errorHandler: function (err) {
                        console.log(err);
                        this.emit("end");
                    }
                }))
                .pipe(gulp.dest(config.scripts.dest));
    };
};
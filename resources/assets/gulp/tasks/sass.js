module.exports = function (gulp, plugins, config) {
    return function () {
        var sources = [];
        for (var i in config.sass.sources) {
            sources[i] = config.sass.dir + "/" + config.sass.sources[i];
        }

        return gulp.src(sources, {base: config.sass.dir})
                .pipe(plugins.plumber({
                    errorHandler: function (err) {
                        console.log(err);
                        this.emit("end");
                    }
                }))
                .pipe(plugins.sass())
                .pipe(plugins.autoprefixer())
                .pipe(plugins.csscomb())
                .pipe(plugins.mmq({
                    log: true
                }))
                .pipe(gulp.dest(config.sass.dest));
    };
};
module.exports = function (gulp, plugins, config) {
    return function () {
        var sources = [];
        for (var i in config.build.views.sources) {
            sources[i] = config.build.views.dir + "/" + config.build.views.sources[i];
        }

        return gulp.src(sources)
                .pipe(plugins.useref({searchPath: config.build.views.search_path}))
                .pipe(plugins.if('*.js', plugins.uglify()))
                .pipe(plugins.if('*.css', plugins.cssnano()))
                .pipe(gulp.dest(config.build.dist + (config.build.views.dest !== "" ? "/" + config.build.views.dest : "")));
    };
};
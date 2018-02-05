module.exports = function (gulp, plugins, config) {
    return function () {
        return gulp.src(config.build.css_js_sources)
                .pipe(plugins.useref({searchPath: config.build.css_js_search_path}))
                .pipe(plugins.if('*.js', plugins.uglify()))
                .pipe(plugins.if('*.css', plugins.cssnano()))
                .pipe(gulp.dest(config.build.dist));
    };
};
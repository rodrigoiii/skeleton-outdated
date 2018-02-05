var del  = require("del"),
    path = require('path');

module.exports = function (gulp, plugins, config) {
    return function () {
        var sources = [];
        for (var i in config.sass.sources) {
            sources[i] = config.sass.dir + "/" + config.sass.sources[i];
        }

        var sass_watcher = gulp.watch(sources, [config.sass.command]);

        sass_watcher.on('change', function (event)
        {
            if (event.type === "deleted")
            {
                var file = path.resolve(config.sass.dest, path.relative(path.resolve(config.sass.dir), event.path));
                var css_file = file.replace(".scss", ".css");

                del.sync(css_file);
            }
        });
    };
};
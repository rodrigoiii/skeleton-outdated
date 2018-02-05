var del  = require("del"),
    path = require('path');

module.exports = function (gulp, plugins, config) {
    return function () {
        var sources = [];
        for (var i in config.scripts.sources) {
            sources[i] = config.scripts.dir + "/" + config.scripts.sources[i];
        }

        var js_watcher = gulp.watch(sources, [config.scripts.command]);

        js_watcher.on('change', function (event)
        {
            if (event.type === "deleted")
            {
                var file = path.resolve('public/js', path.relative(path.resolve(config.scripts.dir), event.path));

                del.sync(file);
            }
        });
    };
};
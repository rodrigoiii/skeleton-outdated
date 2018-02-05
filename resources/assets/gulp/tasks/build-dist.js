var runSequence = require("run-sequence");

module.exports = function (gulp, plugins, config) {
    return function () {
        runSequence('delete:dist', [
            config.sass.command,
            config.scripts.command,
            config.build.views.command,
            config.build.images.command,
            config.build.fonts.command
        ]);
    };
};
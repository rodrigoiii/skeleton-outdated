var del = require("del");

module.exports = function (gulp, plugins, config) {
    return function () {
        del.sync(config.build.dist);
    };
};
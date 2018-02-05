var del = require("del");

module.exports = function (gulp, plugins, config) {
    del.sync(config.build.dist);
};
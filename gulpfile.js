/**
 * node modules
 */
var
// for development
gulp         = require("gulp"),
sass         = require("gulp-sass"),
autoprefixer = require("gulp-autoprefixer"),
csscomb      = require("gulp-csscomb"),
mmq 		 = require("gulp-merge-media-queries"),
plumber      = require("gulp-plumber")
// for build
useref      = require("gulp-useref"),
uglify      = require("gulp-uglify"),
gulpIf      = require("gulp-if"),
cssnano     = require("gulp-cssnano"),
imagemin    = require("gulp-imagemin"),
cache       = require("gulp-cache"),
flatten 	= require("gulp-flatten"),
runSequence = require("run-sequence"),
del 		= require("del"),
path 		= require('path')
;

var settings = {
	basedir: "public",

	sassdir: "resources/assets/sass",
	jsFromdir: "resources/assets/js",

	cssdir: "public/css",
	jsTodir: "public/js",

	phpdir: "resources/views",
	twigdir: "resources/views",

	imgdir: "public",
	fontsdir: "public",
};

// Gulp functions for development
function compiledSass ()
{
	return gulp.src(settings.sassdir + "/**/*.scss", {base: settings.sassdir})
		.pipe(plumber({
			errorHandler: function (err) {
				console.log(err);
				this.emit("end");
			}
		}))
		.pipe(sass())
		.pipe(autoprefixer())
		.pipe(csscomb())
		.pipe(mmq({
			log: true
		}))
		.pipe(gulp.dest(settings.cssdir));
};
function compiledJs()
{
	return gulp.src(settings.jsFromdir + "/**/*.js", {base: settings.jsFromdir})
		.pipe(plumber({
			errorHandler: function (err) {
				console.log(err);
				this.emit("end");
			}
		}))
		.pipe(gulp.dest(settings.jsTodir));
}
function sassWatch ()
{
	var sass_watcher = gulp.watch(settings.sassdir + "/**/*.scss", ['sass']);

	sass_watcher.on('change', function (event)
	{
		if (event.type === "deleted")
		{
			var file = path.resolve('public/css', path.relative(path.resolve(settings.sassdir), event.path));
			var css_file = file.replace(".scss", ".css");

			del.sync(css_file);
		}
	});
};
function jsWatch ()
{
	var js_watcher = gulp.watch(settings.jsFromdir + "/**/*.js", ['js']);

	js_watcher.on('change', function (event)
	{
		if (event.type === "deleted")
		{
			var file = path.resolve('public/js', path.relative(path.resolve(settings.jsFromdir), event.path));

			del.sync(file);
		}
	});
};
function sassjsWatch()
{
	sassWatch();
	jsWatch();
}

// Gulp functions for build
function bundledAsset ()
{
	return gulp.src([settings.phpdir + "/**/*.php", settings.twigdir + "/**/*.twig"])
			.pipe(useref({searchPath: settings.basedir}))
			.pipe(gulpIf('*.js', uglify()))
			.pipe(gulpIf('*.css', cssnano()))
			.pipe(gulp.dest(settings.basedir + "/dist"));
}

function optimizeImages ()
{
	return gulp.src(settings.imgdir + "/**/*.+(png|jpg|jpeg|gif|svg)")
		.pipe(cache(imagemin({
			interlaced: true
		})))
		.pipe(gulp.dest(settings.basedir + "/dist/img"));
}

function flattenFonts ()
{
	return gulp.src([
			settings.basedir + "/**/*.eot",
			settings.basedir + "/**/*.svg",
			settings.basedir + "/**/*.ttf",
			settings.basedir + "/**/*.woff",
			settings.basedir + "/**/*.woff2",
			settings.basedir + "/**/*.otf",
		])
		.pipe(flatten())
		.pipe(gulp.dest(settings.basedir + "/dist/fonts"));
}
function deleteDist ()
{
	del.sync(settings.basedir + "/dist");
}
function build (callback)
{
	runSequence('delete-dist',
		['sass',
		'js',
		'useref',
		'optimize-images',
		'flatten-fonts'],
		callback);
}

// Gulp tasks for development
gulp.task('sass', compiledSass);
gulp.task('js', compiledJs);
gulp.task('sass-watch', ["sass"], sassWatch);
gulp.task('js-watch', ["js"], jsWatch);
gulp.task('dev', ["sass-watch", "js-watch"], sassjsWatch);

// Gulp Tasks for build
gulp.task('useref', bundledAsset);
gulp.task('optimize-images', optimizeImages);
gulp.task('flatten-fonts', flattenFonts);
gulp.task('delete-dist', deleteDist);
gulp.task('build', function (callback)
{
	build(callback);
});

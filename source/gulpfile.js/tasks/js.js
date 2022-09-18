const js = () => {
	return $.gulp.src($.path.js.src, {sourcemaps: $.settings.isDev})
	.pipe($.plugins.fileInclude())
	.pipe($.plugins.babel())
	.pipe($.plugins.terser($.settings.terser))
	.pipe($.gulp.dest($.path.js.dest, {sourcemaps: $.settings.isDev}))
	.pipe($.browserSync.stream());
}

module.exports = js;

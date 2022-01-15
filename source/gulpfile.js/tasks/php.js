const php = () => {
	return $.gulp.src($.path.php.src)
	// .pipe($.plugins.htmlmin($.settings.htmlmin))
	.pipe($.gulp.dest($.path.php.dest))
	.pipe($.browserSync.stream());
}

module.exports = php;
const server = () => {
	$.browserSync.init({
		proxy: "zak",
		/*server: {
			baseDir: $.path.root
		},*/
		//tunnel: true,
		notify: false,
		open: true
	});
}

module.exports = server;
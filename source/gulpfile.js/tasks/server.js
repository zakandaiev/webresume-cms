const server = () => {
	$.browserSync.init({
		proxy: "webresume",
		/*server: {
			baseDir: $.path.root
		},*/
		//tunnel: true,
		notify: false,
		open: true
	});
}

module.exports = server;
const server = () => {
	$.browserSync.init({
		proxy: "webresume.local",
		/*server: {
			baseDir: $.path.root
		},*/
		//tunnel: true,
		notify: false,
		open: true
	});
}

module.exports = server;
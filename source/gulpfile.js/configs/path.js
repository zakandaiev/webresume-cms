const pathSrc = "./src";
const pathDest = "../";

module.exports = {
	root: pathDest,

	clear: [
		pathDest + "**",
		"!" + pathDest + "source",
		"!" + pathDest + ".gitignore",
		"!" + pathDest + "LICENSE",
		"!" + pathDest + "README.md"
	],

	php: {
		src: pathSrc + "/php/**/*.{php,sql}",
		watch: pathSrc + "/php/**/*.{php,sql}",
		dest: pathDest
	},

	sass: {
		src: pathSrc + "/sass/*.{sass,scss}",
		watch: pathSrc + "/sass/**/*.{sass,scss}",
		dest: pathDest + "/css"
	},

	js: {
		src: pathSrc + "/js/*.js",
		watch: pathSrc + "/js/**/*.js",
		dest: pathDest + "/js"
	},

	img: {
		src: pathSrc + "/img/**/*.*",
		watch: pathSrc + "/img/**/*.*",
		dest: pathDest + "/img"
	},

	fonts: {
		src: pathSrc + "/fonts/*.{ttf,woff2}",
		watch: pathSrc + "/fonts/*.{ttf,woff2}",
		dest: pathDest + "/fonts"
	},

	rootFiles: {
		src: pathSrc + "/root-files/**/*.*",
		watch: pathSrc + "/root-files/**/*.*",
		dest: pathDest
	}
};

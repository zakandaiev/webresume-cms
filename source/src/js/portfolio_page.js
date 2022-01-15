// VENDOR
@@include("../../node_modules/@fancyapps/ui/dist/fancybox.umd.js")
@@include("partials/prism.js")

document.addEventListener("DOMContentLoaded", () => {
	// CODE SNIPPETS
	document.querySelectorAll(".code").forEach(snippet => {
		const snippet_title = snippet.querySelector(".code__title");
		if(snippet_title) {
			snippet_title.addEventListener("click", event => {
				event.preventDefault();
				snippet.classList.toggle("active");
			});
		}
	});
});
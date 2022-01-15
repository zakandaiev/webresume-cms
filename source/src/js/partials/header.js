const header = document.getElementById("header");
const footer = document.getElementById("footer");
const page_content = document.querySelector(".page-content");

let isMobile = false;

window.addEventListener("load", UpdateMobileState);
window.addEventListener("resize", UpdateMobileState);
window.addEventListener("fullscreenchange", UpdateMobileState);
window.addEventListener("orientationchange", UpdateMobileState);

function UpdateMobileState() {
	if(window.matchMedia("(max-width: 991px)").matches) {
		isMobile = true;
	} else {
		isMobile = false;
	}
	ResponsiveHeader();
}

function ResponsiveHeader() {
	if(isMobile) {
		page_content.after(footer);
	} else {
		header.append(footer);
	}
}

let doc_height, client_height, doc_scrollTop, doc_scrollTop_percent, bg_position;
window.onscroll = () => {
	if(isMobile) return;
	doc_height = document.documentElement.scrollHeight;
	client_height = document.documentElement.clientHeight;
	doc_scrollTop = window.pageYOffset || document.documentElement.scrollTop;
	doc_scrollTop_percent = (doc_scrollTop / (doc_height - client_height)) * 100;
	/*
		[header.scss]
		background-size: auto calc(100% + 100px);
		background-position: center 0;
	*/
	bg_position = (100 * doc_scrollTop_percent) / 100; // (100px * doc_scrollTop_percent) / 100%
	header.style.backgroundPosition = `center -${bg_position}px`;
}
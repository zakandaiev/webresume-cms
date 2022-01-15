const timeline_add = document.getElementById("timeline-add");
const timeline_item = `
	<div class="timeline__item">
		<div class="timeline__left">
			<h4 class="timeline__title" contenteditable data-name="section_timeline">Title</h4>
			<p class="timeline__subtitle" contenteditable data-name="section_timeline">Subtitle</p>
		</div>
		<div class="timeline__right">
			<h4 class="timeline__title" contenteditable data-name="section_timeline">Title</h4>
			<p class="timeline__subtitle" contenteditable data-name="section_timeline">Subtitle</p>
		</div>
	</div>
`;

if(timeline_add) {
	timeline_add.addEventListener("click", event => {
		event.preventDefault();
		let timeline = timeline_add.closest(".section").querySelector(".timeline");
		if(!timeline) {
			timeline = document.createElement("div");
			timeline.classList.add("timeline");
			timeline_add.closest(".section").insertBefore(timeline, timeline_add);
		}
		timeline.innerHTML += timeline_item;
	});
}

// CONTENTEDITABLE HANDLE
document.querySelectorAll("[contenteditable]").forEach(element => {
	element.addEventListener("paste", event => {
		event.preventDefault();
		let clipboard_text = "";
		if(event.clipboardData || event.originalEvent.clipboardData) {
			clipboard_text = (event.originalEvent || event).clipboardData.getData("text/plain");
		} else if(window.clipboardData) {
			clipboard_text = window.clipboardData.getData("Text");
		}
		if(document.queryCommandSupported("insertText")) {
			document.execCommand("insertText", false, clipboard_text);
		} else {
			document.execCommand("paste", false, clipboard_text);
		}
	});
});
document.addEventListener("focusout", event => {
	const element = event.target;
	if(element.hasAttribute("contenteditable") && element.hasAttribute("data-name")) {
		let element_content = element.innerHTML;
		
		const replace_map = {amp: '&', lt: '<', gt: '>', quot: '"', '#039': "'"};
		element_content = element_content.replace(/&([^;]+);/g, (m, c) => replace_map[c]);
		element.innerHTML = element_content;

		sendData(element);
	}
});

// SORTABLE HANDLE
document.querySelectorAll(".sortable").forEach(element => {
	const sortable = new Sortable(element, {
		handle: ".sortable__handle",
		animation: 150,
		onUpdate: () => sendData(element)
	});
});

function sendData(element) {
	let fetch_url = "/live_edit";

	const element_name = element.getAttribute("data-name");
	let element_content = element.innerHTML;
	
	if(!element_name) {
		return;
	}

	if(element_name == "section_timeline") {
		element_content = [];
		const timeline = element.closest(".timeline");
		if(!timeline) {
			return;
		}
		timeline.querySelectorAll(".timeline__item").forEach(item => {
			const item_obj = {
				left_title: item.querySelector(".timeline__left > .timeline__title").innerHTML,
				left_subtitle: item.querySelector(".timeline__left > .timeline__subtitle").innerHTML,
				right_title: item.querySelector(".timeline__right > .timeline__title").innerHTML,
				right_subtitle: item.querySelector(".timeline__right > .timeline__subtitle").innerHTML
			}
			element_content.push(item_obj);
		});
		element_content = JSON.stringify(element_content);
	}

	if(element_name == "section_skills") {
		element_content = [];
		const table = element.closest("table");
		if(!table) {
			return;
		}
		table.querySelectorAll("tbody tr").forEach(item => {
			const item_obj = {
				left: item.querySelector(":nth-child(1)").innerHTML,
				right: item.querySelector(":nth-child(2)").innerHTML
			}
			element_content.push(item_obj);
		});
		element_content = JSON.stringify(element_content);
	}

	if(element_name == "person_socials") {
		element_content = [];
		const table = element.closest("table");
		if(!table) {
			return;
		}
		table.querySelectorAll("tbody tr").forEach(item => {
			const item_obj = {
				url: item.querySelector(":nth-child(1)").innerHTML,
				icon: item.querySelector(":nth-child(2)").innerHTML
			}
			element_content.push(item_obj);
		});
		element_content = JSON.stringify(element_content);
	}

	if(element_name == "sort_code") {
		fetch_url = "/portfolio/sort_code";
		element_content = [];
		const sortable = element.closest(".sortable");
		if(!sortable) {
			return;
		}
		sortable.querySelectorAll(".code").forEach((item, index) => {
			const item_obj = {
				code_id: item.getAttribute("data-code-id"),
				order: index + 1
			}
			element_content.push(item_obj);
		});
		element_content = JSON.stringify(element_content);
	}
	
	element.setAttribute("disabled", true);

	let formData = new FormData();
	formData.set("csrf_token", csrf_token);
	formData.set("name", element_name);
	formData.set("content", element_content);

	fetch(fetch_url, {method: "POST", body: formData})
	.then(response => response.json())
	.then(data => {
		if(data.success === 1) {
			if(data.message.length) makeAlert("success", data.message);
		} else {
			if(data.message.length) makeAlert("error", data.message);
		}
		element.setAttribute("disabled", false);
	})
	.catch(error => {
		if(error) makeAlert("error", error);
		element.setAttribute("disabled", false);
	});
}
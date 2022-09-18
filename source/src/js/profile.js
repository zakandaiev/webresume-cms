/* ==========  Third party  ========== */
@@include("../../node_modules/sortablejs/Sortable.min.js")

document.addEventListener("DOMContentLoaded", () => {
	// WYSIWYG
	document.querySelectorAll(".contenteditable").forEach(element => {
		const textarea = element.previousElementSibling;
		if(textarea && textarea.nodeName == "TEXTAREA") {
			textarea.classList.add("hidden");
			element.addEventListener("focusout", event => {
				let element_content = element.innerHTML;

				const replace_map = {amp: '&', lt: '<', gt: '>', quot: '"', '#039': "'"};
				element_content = element_content.replace(/&([^;]+);/g, (m, c) => replace_map[c]);

				element.innerHTML = element_content;
				textarea.value = element_content;
			});
		}
	});

	// CODE WYSIWYG
	document.querySelectorAll('textarea[name="code"]').forEach(textarea => {
		textarea.style.height = textarea.scrollHeight + 'px';
		textarea.addEventListener("input", event => {
			textarea.style.height = textarea.scrollHeight + 'px';
		});
	});

	// LIVE EDIT
	@@include("partials/live_edit.js")

	// TABLE TOOLS
	@@include("partials/table_tools.js")

	// TIMELINE
	const timeline_del = document.querySelectorAll(".timeline [data-del]");
	if(timeline_del) {
		timeline_del.forEach(del => {
			del.addEventListener("click", event => {
				event.preventDefault();
				const confirmation = confirm("Do you confirm the deletion?");
				if (confirmation) {
					let formData = new FormData();
					formData.set("csrf_token", csrf_token);
					const data = del.getAttribute("data-del");
					if(!data) {
						makeAlert("error", "Blank attributes");
					}
					const fetch_url = data.split("/")[0];
					const item_key = fetch_url.split("_")[1] + "_id";
					const item_id = data.split("/")[1];
					formData.append(item_key, item_id);
					fetch(fetch_url, {method: "POST", body: formData})
					.then(response => response.json())
					.then(data => {
						if(data.success === 1) {
							const timeline_item = del.closest(".timeline__item");
							if(timeline_item) {
								timeline_item.remove();
							}
							makeAlert("success", data.message);
						} else {
							makeAlert("error", data.message);
						}
					})
					.catch(error => makeAlert("error", error));
				}
			});
		});
	}
});

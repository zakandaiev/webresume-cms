const forms = document.querySelectorAll("form");

const form_file = src => {
	const file_ext = src.substr(src.lastIndexOf(".") + 1, src.length).toLowerCase();
	if(file_ext === "jpg" || file_ext === "jpeg" || file_ext === "png" || file_ext === "gif" || file_ext === "webp" || file_ext === "svg") {
		return `<div class="form-files__item" data-src="${src}"><img src="/${src}"></div>`;
	} else if(file_ext === "pdf") {
		return `<div class="form-files__item" data-src="${src}"><span><svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zm-22.6 22.7c2.1 2.1 3.5 4.6 4.2 7.4H256V32.5c2.8.7 5.3 2.1 7.4 4.2l83.9 83.9zM336 480H48c-8.8 0-16-7.2-16-16V48c0-8.8 7.2-16 16-16h176v104c0 13.3 10.7 24 24 24h104v304c0 8.8-7.2 16-16 16zm-22-171.2c-13.5-13.3-55-9.2-73.7-6.7-21.2-12.8-35.2-30.4-45.1-56.6 4.3-18 12-47.2 6.4-64.9-4.4-28.1-39.7-24.7-44.6-6.8-5 18.3-.3 44.4 8.4 77.8-11.9 28.4-29.7 66.9-42.1 88.6-20.8 10.7-54.1 29.3-58.8 52.4-3.5 16.8 22.9 39.4 53.1 6.4 9.1-9.9 19.3-24.8 31.3-45.5 26.7-8.8 56.1-19.8 82-24 21.9 12 47.6 19.9 64.6 19.9 27.7.1 28.9-30.2 18.5-40.6zm-229.2 89c5.9-15.9 28.6-34.4 35.5-40.8-22.1 35.3-35.5 41.5-35.5 40.8zM180 175.5c8.7 0 7.8 37.5 2.1 47.6-5.2-16.3-5-47.6-2.1-47.6zm-28.4 159.3c11.3-19.8 21-43.2 28.8-63.7 9.7 17.7 22.1 31.7 35.1 41.5-24.3 4.7-45.4 15.1-63.9 22.2zm153.4-5.9s-5.8 7-43.5-9.1c41-3 47.7 6.4 43.5 9.1z"></path></svg></span></div>`;
	}
	return `<div class="form-files__item" data-src="${src}"><span><svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34zm-22.6 22.7c2.1 2.1 3.5 4.6 4.2 7.4H256V32.5c2.8.7 5.3 2.1 7.4 4.2l83.9 83.9zM336 480H48c-8.8 0-16-7.2-16-16V48c0-8.8 7.2-16 16-16h176v104c0 13.3 10.7 24 24 24h104v304c0 8.8-7.2 16-16 16zm-48-244v8c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm0 64v8c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12zm0 64v8c0 6.6-5.4 12-12 12H108c-6.6 0-12-5.4-12-12v-8c0-6.6 5.4-12 12-12h168c6.6 0 12 5.4 12 12z"></path></svg></span></div>`;
};

forms.forEach(form => {
	form.insertAdjacentHTML("beforeend", loader);

	formBehavior(form);
	formFilesUpload(form);

	form.addEventListener("submit", event => {
		event.preventDefault();
		
		disableForm(form);

		let formData = new FormData(form);
		formData.set("csrf_token", csrf_token);

		form.querySelectorAll(".form-files").forEach(form_files => {
			let files_array = [];
			let input = form_files.nextElementSibling;
			let input_key = input.getAttribute("name");
			form_files.querySelectorAll(".form-files__item").forEach(file => {
				files_array.push(file.getAttribute("data-src"));
			});
			if(files_array.length > 1) {
				formData.set(input_key, JSON.stringify(files_array));
			} else {
				if(typeof files_array[0] === "undefined") {
					formData.set(input_key, "");
				} else if(input.hasAttribute("multiple")) {
					formData.set(input_key, JSON.stringify(files_array));
				} else {
					formData.set(input_key, files_array[0]);
				}
			}
		});

		fetch(form.action, {method: "POST", body: formData})
		.then(response => response.json())
		.then(data => {
			if(data.success === 1) {
				if(data.message.length) {
					makeAlert("success", data.message);
				}
				if(form.hasAttribute("data-redirect")) {
					const redirect = form.getAttribute("data-redirect");
					if(redirect === "this") {
						document.location.reload();
					} else {
						window.location.href = redirect;
					}
				}
				if(form.hasAttribute("data-reset")) {
					form.reset();
				}
			} else {
				makeAlert("error", data.message);
			}
			enableForm(form);
		})
		.catch(error => {
			makeAlert("error", error);
			enableForm(form);
		});
	});
});

function formBehavior(form) {
	const controls = form.querySelectorAll("[data-form-behavior]");

	function hideItems(control) {
		let hide = control.getAttribute("data-hide");
		if(control.getAttribute("type") === "checkbox" && !control.checked) {
			if(hide) {
				hide += "," + control.getAttribute("data-show");
			} else {
				hide = control.getAttribute("data-show");
			}
		}
		if(control.getAttribute("type") === "radio" && !control.checked) {
			hide = null;
		}
		if(hide) {
			hide.split(",").forEach(to_hide => {
				const item = form.querySelector("[name='"+to_hide+"']");
				const parent = item.parentElement;
				if(parent.classList.contains("form-control")) {
					parent.classList.add("hidden");
				} else {
					item.classList.add("hidden");
				}
			});
		}
	}

	function showItems(control) {
		let show = control.getAttribute("data-show");
		if(control.getAttribute("type") === "checkbox" && !control.checked) {
			show = null;
		}
		if(control.getAttribute("type") === "radio" && !control.checked) {
			show = null;
		}
		if(show) {
			show.split(",").forEach(to_show => {
				const item = form.querySelector("[name='"+to_show+"']");
				const parent = item.parentElement;
				if(parent.classList.contains("form-control")) {
					parent.classList.remove("hidden");
				} else {
					item.classList.remove("hidden");
				}
			});
		}
	}

	function textToUrl(text) {
		return text.toLowerCase().trim().replace(/\s+/g, " ").replace(/\s/g, "_").replace(/-/g, "_").replace(/\W/g, "").replace(/_/g, "-");
	};
	
	controls.forEach(control => {
		// on form init
		if(control.getAttribute("data-form-behavior") === "visibility") {
			hideItems(control);
			showItems(control);
		}
		// on form change
		control.addEventListener("change", event => {
			if(control.getAttribute("data-form-behavior") === "visibility") {
				hideItems(control);
				showItems(control);
			}
			if(control.getAttribute("data-form-behavior") === "text_to_url") {
				if(control.hasAttribute("data-target")) {
					control.getAttribute("data-target").split(",").forEach(target => {
						const target_item = form.querySelector('[name='+target+']');
						if(target_item) {
							target_item.value = textToUrl(control.value);
						}
					});
				} else {
					control.value = textToUrl(control.value);
				}
			}
		});
	});
}

function formFilesUpload(form) {
	const file_inputs = form.querySelectorAll('input[type="file"]');

	file_inputs.forEach(input => {
		input.addEventListener("change", event => {
			const upload_type = input.getAttribute("data-type");
			if(!upload_type) {
				return;
			}
			let upload_api;
			if(upload_type === "image") {
				upload_api = "/upload/images";
			} else if(upload_type === "resume") {
				upload_api = "/upload/resume";
			}
			if(!upload_api.length) {
				return;
			}
			const fileList = event.target.files;
			let formData = new FormData();
			formData.set("csrf_token", csrf_token);
			Array.from(fileList).forEach(file => {
				formData.append("files[]", file);
			});
			fetch(upload_api, {method: "POST", body: formData})
			.then(response => response.json())
			.then(data => {
				if(data.success === 1) {
					let output = input.previousElementSibling.innerHTML;
					const files_array = data.message;
					if(!input.hasAttribute("multiple")) {
						output = form_file(files_array[0]);
					} else {
						files_array.forEach(file => {
							output += form_file(file);
						});
					}
					input.previousElementSibling.innerHTML = output;
					makeAlert("success", "Files uploaded");
				} else {
					makeAlert("error", data.message);
				}
			})
			.catch(error => {
				makeAlert("error", error);
			});
		});
	});

	form.addEventListener("click", event => {
		const image = event.target.closest(".form-files__item");
		if(image) {
			image.remove();
		}
	});
}

function disableForm(form) {
	form.classList.add("submit");
	form.querySelector('[type="submit"]').disabled = true;
}
function enableForm(form) {
	form.classList.remove("submit");
	form.querySelector('[type="submit"]').disabled = false;
}
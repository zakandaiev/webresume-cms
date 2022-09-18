function makeAlert(type, text, duration) {
	if(!text || !text.length) {
		return false;
	}

	let container = document.querySelector('.toasts');
	if(!container) {
		container = document.createElement('div');
		container.classList.add('toasts');
		document.body.appendChild(container);
	}

	let toast = document.createElement('div');
	toast.classList.add('toasts__item');
	if(type) {
		toast.classList.add(type);
	}

	let toast_icon = document.createElement('i');
	toast_icon.classList.add('toasts__icon');
	if(type) {
		toast_icon.classList.add(type);
	}

	let toast_text = document.createElement('span');
	toast_text.classList.add('toasts__text');
	toast_text.textContent = text;

	toast.appendChild(toast_icon);
	toast.appendChild(toast_text);

	container.appendChild(toast);

	toast.addEventListener('click', () => toast_remove(container, toast));

	setTimeout(() => toast_remove(container, toast), duration ? duration : 5000);

	function toast_remove(container, toast) {
		toast.classList.add('disappear');
		setTimeout(() => {
			toast.remove();
			if(container && container.childElementCount <= 0) {
				container.remove();
			}
		}, 500);
	}

	return true;
}

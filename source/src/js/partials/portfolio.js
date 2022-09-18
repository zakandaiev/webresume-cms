const portfolio = document.querySelector(".portfolio");
const portfolio_load_more = document.getElementById("portfolio-load-more");
const portfolio_item = function(title, url, image, teaser) {
	let img;
	if(image) {
		img = image.replace(" ", "%20");
	} else {
		img = 'img/no_image.jpg';
	}
	return `
		<div class="portfolio__item">
			<a class="portfolio__img" href="/portfolio/${url}"><img src="/${img}" alt="${title}"></a>
			<h3 class="portfolio__title">${title}</h3>
			<p class="portfolio__teaser">${teaser}</p>
		</div>
	`;
};

AdjustGalleryImages();
AdjustGalleryImages(".portfolio__img");

// LOAD MORE
if(portfolio && portfolio_load_more) {
	let portfolio_length = portfolio_limit = portfolio_output = 0;

	portfolio_limit = portfolio_output = portfolio.querySelectorAll(".portfolio__item").length;

	portfolio_load_more.addEventListener("click", event => {
		const button = event.currentTarget;
		const button_text = event.currentTarget.textContent;

		button.innerHTML = loader;
		button.disabled = true;

		let formData = new FormData();
		formData.set("csrf_token", csrf_token);

		fetch("/api/portfolio", {method: "POST", body: formData})
		.then(response => response.json())
		.then(data => {
			button.innerHTML = button_text;
			button.disabled = false;
			portfolio_length = data.length;
			data = data.slice(portfolio_output, portfolio_output + portfolio_limit);
			data.forEach(function(item, id) {
				let teaser = new Date(item.cdate).toLocaleDateString();
				if(item.details) {
					const details = JSON.parse(item.details);
					if(details && details.date) teaser = details.date;
				}
				portfolio.insertAdjacentHTML("beforeend", portfolio_item(item.title, item.url, item.main_image, teaser));
			});
			if(portfolio_length <= portfolio_output + portfolio_limit) {
				button.closest(".section__footer").remove();
			}
			portfolio_output += portfolio_limit;
			AdjustGalleryImages(".portfolio__img");
		})
		.catch(error => {
			button.outerHTML = error;
		});
	});
}

function AdjustGalleryImages(class_name = ".gallery__item") {
	document.querySelectorAll(class_name + ":not(.long)").forEach(item => {
		const image = item.querySelector("img");
		if(!image) {
			return;
		}
		function Adjust() {
			const aspect_ratio = image.naturalWidth / image.naturalHeight;
			if(aspect_ratio < 0.8) {
				item.classList.add("long");
			}
		}
		Adjust();
		image.onload = () => {
			Adjust();
			setTimeout(() => Adjust(), 1000);
		};
	});
}

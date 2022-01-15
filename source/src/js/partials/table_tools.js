const tables = document.querySelectorAll("table");
const skills_item = `
	<tr>
		<th contenteditable data-name="section_skills">Essence</th>
		<td contenteditable data-name="section_skills">Description</td>
		<td class="table__tools"></td>
	</tr>
`;

tables.forEach(table => {
	const table_add = table.parentElement.previousElementSibling.querySelector("[data-add]");
	const table_del = table.querySelectorAll("[data-del]");
	const table_count =table.parentElement.previousElementSibling.querySelector("[data-table-count]");

	if(table_add) {
		table_add.addEventListener("click", event => {
			event.preventDefault();
			table.querySelector("tbody").innerHTML += skills_item;
			if(table_count) {
				table_count.textContent = parseInt(table_count.textContent) + 1;
			}
		});
	}

	table_del.forEach(del => {
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
						const table_row = del.closest(".table__tools").parentElement;
						if(table_row) {
							table_row.remove();
						}
						if(table_count && parseInt(table_count.textContent) > 1) {
							table_count.textContent -= 1;
						} else {
							document.location.reload();
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
});
import {setCookie, getCookie, deleteCookie} from "./cookie";


const searchActivity = $ => {

	let form = $('form#js-search-activity');
	let whereBtn = form.find('#where-group');

	form.on('submit', function (e) {
		e.preventDefault();
		const base_url = $('#search_base_url').val();

		// Make form data readable
		let formData = [
			['what', e.target[0].value],
			['who', e.target[1].value],
			['where', e.target[2].value],
			// ['post_type', 'product']
		];

		// Force use dept geoloc
		let where = formData[2][1];
		if (where === "0") {
			whereBtn.addClass('invalid');
			return false;
		}

		// Save as cookie where user is
		let selected = e.target[2].selectedOptions[0].dataset.dept;
		setCookie('whereIam', selected);

		// Build param
		let params = '?';
		formData.map((value, index, array) => {
			params += '&' + value[0] + '=' + value[1];
		});

		// redirect to search page
		window.location.href = base_url + params;

		return false;
	})
};

export {searchActivity}

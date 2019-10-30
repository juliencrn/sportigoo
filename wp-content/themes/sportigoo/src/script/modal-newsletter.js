import {getCookie, setCookie} from "./cookie";

const modalNewsletter = $ => {

	$('form#mjForm').on('submit', () => setCookie('newsletter', 1, 365));

	let newsletterCookie = getCookie('newsletter');

	if (!newsletterCookie) {

		const newsletter = $("#newsletter_modal");

		if (newsletter.length) {

			// setTimeout(() => newsletter.modal(), 12000);

			newsletter.on($.modal.CLOSE, () => setCookie('newsletter', 1, 30));

		}

	}

};

export {modalNewsletter}

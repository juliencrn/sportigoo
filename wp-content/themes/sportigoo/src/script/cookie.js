const cookie = $ => {
	window.addEventListener('load', function () {

		const cookieElt = $('#cookie');
		window.cookieconsent.initialise({
			palette: {
				popup: {
					background: '#eb6c44',
					text: '#ffffff',
				},
				button: {
					background: '#ffffff',
				},
			},
			theme: 'classic',
			position: 'bottom-right',
			content: {
				message: cookieElt.find('#cookie_text').val(),
				dismiss: cookieElt.find('#cookie_ok').val(),
				link: cookieElt.find('#cookie_more').val(),
				href: cookieElt.find('#cookie_href').val()
			},
		})
	})
};


/**
 * Cookies getter & setter
 * @link https://plainjs.com/javascript/utilities/set-cookie-get-cookie-and-delete-cookie-5/
 *
 */
function getCookie(name) {
	var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
	return v ? v[2] : null;
}

function setCookie(name, value, days = 30) {
	var d = new Date;
	d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
	document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
}

function deleteCookie(name) {
	setCookie(name, '', -1);
}

export {cookie, deleteCookie, getCookie, setCookie}

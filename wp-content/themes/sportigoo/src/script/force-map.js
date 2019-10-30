import {getCookie, setCookie} from "./cookie";
import {svgMap} from './svg-map'

const forceMap = $ => {

	let region = getCookie('whereIam');

	if (!region || region == "0") {

		const mapModal = $("#map_modal");

		if (mapModal.length) {
			mapModal.modal({
				escapeClose: false,
				clickClose: false,
				showClose: false
			});

			mapModal.on($.modal.OPEN, () => svgMap())

			$('body').on('map:select', () => $.modal.close());
		}

	}



};

export {forceMap}
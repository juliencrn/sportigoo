import {getCookie, setCookie} from "./cookie";

const svgMap = $ => {

	const map = $('#mapsvg');

	if (map.length) {
		map.mapSvg({

			source: map.attr('data-url'),
			width: "500px",
			responsive: true,

			colors: {
				base: '#1d467a',
				background: 'transparent',
				stroke: 'white',
				selected: '#E7511E',
				hover: '#E7511E',
			},

			onClick: function () {
				let body = $('body');
				let dept = this.id.split("-")[1];
				//body.trigger('map:select');
				setCookie('whereIam', dept);
				window.location.href = $('#permalink_activity').val()
			},

			afterLoad: function () {
				let dept = getCookie('whereIam');
				if (dept) {
					this.selectRegion("FR-" + dept)
				}
				const regions = map.find('.mapsvg-region');
				regions.each(function () {
					let id = $(this).attr('id').split('-')[1];

					$(this).attr('data-id', id)
				})

			},

		});
	}

};


export {svgMap}

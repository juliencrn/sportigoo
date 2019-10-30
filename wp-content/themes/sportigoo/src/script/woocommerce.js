const woocommerce = $ => {
	const body = $('body');

	cleanWCString()
	body.on('updated_checkout', function(){
		cleanWCString()
	});

	function cleanWCString() {

		// Cart & Checkout
		const texts = body.find('.variation dt')
		const texts2 = body.find('.woocommerce-table__product-name .wc-item-meta .wc-item-meta-label');

		texts.each(cleanup)
		texts2.each(cleanup)

		function cleanup () {
			let copy = true;
			let text = $(this).text().split('').filter(i => {
				if (i === '(') {
					copy = false;
					i = '';
				} else if (i === ')') {
					copy = true;
					i = '';
					return false;
				}
				return copy;
			});
			$(this).text(text.join(''))
		}
	}

};

export {woocommerce}
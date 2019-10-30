jQuery(function ($) {

	// Form fade in
	$("#loginform,#nav,#backtoblog, div h1 a")
		.css("display", "none")
		.fadeIn(1500);

	// Auto check remember me checkbox
	$('#rememberme').prop('checked', true);

});
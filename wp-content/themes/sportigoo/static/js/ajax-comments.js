jQuery(function($){

	// load more button click event
	$('.loadmore_comments').click( function(){
		let button = $(this);
		let buttonText = $("#loadmore_comments_text").val();
		let permalink = $('#permalink').val();

		// decrease the current comment page value
		cpage--;

		$.ajax({
			url : ajaxurl, // AJAX handler, declared before
			data : {
				'action': 'load_more_comments', // wp_ajax_cloadmore
				'post_id': parent_post_id, // the current post
				'cpage' : cpage, // current comment page
			},
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.text('...'); // preloader here
			},
			success : function( data ){
				if( data ) {
					// Paste comments
					let outputData = $('.commentaires__reponses');
					outputData.append( data );

					// Refresh data
					outputData.find('a.comment-reply-link').each(function() {
						let replyLink = permalink + "comment-page-"+cpage+"/#comment-" + $(this).attr('data-commentid');
						$(this).attr('href', replyLink)
					});


					button.text(buttonText);
					// if the last page, remove the button
					if ( cpage == 1 )
						button.remove();
				} else {
					button.remove();
				}
			}
		});
		return false;
	});

});
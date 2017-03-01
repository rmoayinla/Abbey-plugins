//javascript//
(function($) {
	$( document ).ready( function(){
		$(document).on( "click", ".popup-post", function(ev){
			ev.preventDefault();
			var _this, postData, popup, ajaxUrl;
			_this = $( this );
			ajaxUrl  = _this.data( "url" );
			postData = {
				arp_post_id: _this.data( "post-id" ),
				arp_post_type: _this.data( "post-type" ),
				action: "abbey_recent_posts"
			};

			if( $.magnificPopup ){
				popup = $.magnificPopup.instance;
				
				$.ajax({
					url: ajaxUrl,
					data: postData, 
					type: "POST",
					success: 	function( data ){
						var content = popup.content; 
						content.removeClass('mini-popup').addClass('full-popup');
						content.find(".popup-body").html(data).fadeIn("slow");
						
					},
					error: function ( xhr, status, message){
						alert( status + ": "+message );
					}, 
					beforeSend: function( xhr ){
						popup.open({
							items: {
								type: "inline",
								src: "<div class='mini-popup'><div class='popup-body'><span class='fa fa-spinner fa-spin fa-fw'></span> Loading . . .</div></div>" 
							}
						});
					}, 
					complete: function (  xhr ){
						popup.content.addClass("fade-in");
					}

				});
				
			}
			
		} );

	} );

})( jQuery );
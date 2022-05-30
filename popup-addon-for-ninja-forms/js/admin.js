( function ( $ ) {
	$( function () {
		$( 'body' ).on( 'change', '#nf-popups-settings-trigger', function () {
			if ( $( this ).val() == 'click' ) {
				$( '.nf-popups-metabox-delay-row' ).hide();
				$( '.nf-popups-metabox-trigger-class-row' ).show();
				$( '.shortcode-step-2' ).fadeIn();
				update_triger_shortcode();
			} else {
				$( '.nf-popups-metabox-trigger-class-row' ).hide();
				$( '.nf-popups-metabox-delay-row' ).show();
				$( '.shortcode-step-2' ).hide()
			}
		} );
		$( 'body' ).on( 'blur', '#nf_popups_tigger_id', function(){
			update_triger_shortcode();
		});
	} )


	function update_triger_shortcode(){
		var trigger_id = $( '#nf_popups_tigger_id' ).val();
		var first_character = trigger_id.charAt( 0 );
		
		if ( first_character == '.' ) {
			trigger_id =trigger_id.slice( 1 );
			var attr = 'class="' + trigger_id + '"';
		} else {
			trigger_id =trigger_id.slice( 1 );
			var attr = 'id="' + trigger_id + '"';
		}
		$( '.popup-trigger-btn-code' ).text( '<button ' + attr + '>Button Text</button>' );
	}
} )( jQuery );
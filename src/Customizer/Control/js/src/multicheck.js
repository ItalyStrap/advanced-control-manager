jQuery( document ).ready( function( $ ) {

	/* === Multiple Checkbox Control === */

	/**
	 * @author https://github.com/alispx
	 * @link https://github.com/alispx/jogja-core/blob/e1a9367f1f0a4b2278410df5d272dc2ce4bfba6b/addons/customizer/assets/js/mie-methods.js
	 */
	$( '.customize-control-multicheck input[type="checkbox"]' ).on(
		'change',
		function() {
			var checkbox_values = $( this ).parents( '.customize-control-multicheck' ).find( 'input[type="checkbox"]:checked' ).map(
				function() {
					return this.value;
				}
				).get().join( ',' );

			$( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
		}
	);

} ); // jQuery( document ).ready
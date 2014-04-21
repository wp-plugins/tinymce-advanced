// TinyMCE Advanced
( function($) {
	tadvSortable = {

		init : function() {
			var self = this;

			$('#tb1').sortable({
				connectWith: '#tb2, #tb3, #tb4, #unused',
				items : 'li',
				cursor: 'move',
				stop : self.update,
				revert : true,
				opacity : 0.7,
				containment : '#contain'
			});

			$('#tb2').sortable({
				connectWith: '#tb1, #tb3, #tb4, #unused',
				items : 'li',
				stop : self.update,
				revert : true,
				opacity : 0.7,
				containment : '#contain'
			});

			$('#tb3').sortable({
				connectWith: '#tb1, #tb2, #tb4, #unused',
				items : 'li',
				stop : self.update,
				revert : true,
				opacity : 0.7,
				containment : '#contain'
			});

			$('#tb4').sortable({
				connectWith: '#tb1, #tb2, #tb3, #unused',
				items : 'li',
				stop : self.update,
				revert : true,
				opacity : 0.7,
				containment : '#contain'
			});

			$('#unused').sortable({
				connectWith: '#tb1, #tb2, #tb3, #tb4',
				items : 'li',
				stop : self.update,
				revert : true,
				opacity : 0.7,
				containment : '#contain'
			});

			this.update();

			$(window).resize( function() {
  				self.update();
			});
		},

		reset : function() {
			var el = $('#unused'), el_node = el.get(0), last, w, i;

			if ( !el.length )
				return;

			$(document.body).removeClass('wp-adv-error length-error');

			$('.container').each( function( num, ul ) {
			    var kids = ul.childNodes, tbwidth = ul.clientWidth, W = 0;

			    for ( i in kids ) {
					if ( w = kids[i].offsetWidth )
						W += w;
				}

			    if ( ( W + 8 ) > tbwidth )
					$(document.body).addClass('length-error');
			});

			if ( el_node.childNodes.length > 6 ) {
				last = el_node.lastChild.previousSibling;
			    el.height( last.offsetTop + last.offsetHeight + 30 );
			} else {
				el.height(60);
			}
		},

		update : function(e, ui) {
			var toolbar_id;

			tadvSortable.reset();

			if ( ui && ( toolbar_id = ui.item.parent().attr('id') ) )
				ui.item.find('input.tadv-button').attr('name', toolbar_id + '[]');
		}
	}

	$( document ).ready( function() {
		var $importElement = $('#tadv-import'),
			$importError = $('#tadv-import-error');

		tadvSortable.init();

		$( '#menubar' ).on( 'change', function() {
			$( '#tadv-menu-img' ).toggleClass( 'enabled', $(this).prop('checked') );
		});

		$('#tadv-export-select').click( function() {
			$('#tadv-export').focus().select();
		});

		$importElement.change( function() {
			$importError.empty();
		});

		$('#tadv-import-verify').click( function() {
			var string;

			string = ( $importElement.val() || '' ).replace( /^[^{]*/, '' ).replace( /[^}]*$/, '' );
			$importElement.val( string );

			try {
				JSON.parse( string );
				$importError.text( 'No errors.' );
			} catch( error ) {
				$importError.text( error );
			}
		});
	});
}(jQuery));

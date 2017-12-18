/*---------------------Dialog backend-----------------------*/
var RMFtooltip;

( function( $ ) {
	var editor,
		inputs = {};

	RMFtooltip = {
		init: function() {
			inputs.wrap = $('#rmf-tooltip-wrap');
			inputs.dialog = $( '#rmf-tooltip' );
			inputs.backdrop = $( '#rmf-tooltip-backdrop' );
			inputs.submit = $( '#rmf-tooltip-submit' );
			inputs.close = $( '#rmf-tooltip-close' );

			// Input
			inputs.text = $( '#rmf-tooltip-text' );
			inputs.tip = $( '#rmf-tooltip-tip' );

			// Bind event handlers
			inputs.submit.click( function( event ) {
				event.preventDefault();
				RMFtooltip.update();
			});
			inputs.close.add( inputs.backdrop ).add( '#rmf-tooltip-cancel a' ).click( function( event ) {
				event.preventDefault();
				RMFtooltip.close();
			});

		},

		open: function( editorId ) {
			var ed,
				$body = $( document.body );

			$body.addClass( 'modal-open' );

			RMFtooltip.range = null;

			if ( editorId ) {
				window.wpActiveEditor = editorId;
			}

			if ( ! window.wpActiveEditor ) {
				return;
			}

			this.textarea = $( '#' + window.wpActiveEditor ).get( 0 );

			if ( typeof tinymce !== 'undefined' ) {
				// Make sure the tooltip wrapper is the last element in the body,
				// or the inline editor toolbar may show above the backdrop.
				$body.append( inputs.backdrop, inputs.wrap );

				ed = tinymce.get( wpActiveEditor );

				if ( ed && ! ed.isHidden() ) {
					editor = ed;
				} else {
					editor = null;
				}
			}
			inputs.wrap.show();
			inputs.backdrop.show();

		},

		close: function() {
			$( document.body ).removeClass( 'modal-open' );
			RMFtooltip.textarea.focus();
			inputs.backdrop.hide();
			inputs.wrap.hide();
			RMFtooltip_tip = inputs.tip.val();
			RMFtooltip_text = inputs.text.val();
			inputs.tip.val('');
			inputs.text.val('');
		},
		update: function update () {
			/*var attrs = RMFtooltip.getAttrs(),
				link, text;*/
			RMFtooltip.close();
			editor.focus();
			if (RMFtooltip_text != null && RMFtooltip_text != '' && RMFtooltip_tip != null && RMFtooltip_tip != ''){
				editor.execCommand('mceInsertContent', false, '[tooltip tip="'+RMFtooltip_tip+'"]'+RMFtooltip_text+'[/tooltip]');
			}
			editor.nodeChanged();
		},
	}
	$( document ).ready( RMFtooltip.init );
})( jQuery );
/*---------------Integrate with TinyMCE---------------------*/
(function() {
	tinymce.create('tinymce.plugins.RMFtooltip', {
		init : function(ed, url) {
			ed.addButton('RMFtooltip', {
				title : 'ToolTip',
				image : url+'/RMFtooltipbutton.png',
				cmd : 'RMFtooltip_cmd'
			});
			ed.addCommand('RMFtooltip_cmd', function() {
				window.RMFtooltip && window.RMFtooltip.open( ed.id );
			});

		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : "Responsive Mobile-Friendly Tooltip",
				author : 'ItayXD',
				authorurl : 'itayxd.com',
				infourl : 'https://github.com/ItayXD/responsive-tooltip',
				version : "1.6.6"
			};
		}
	});
	tinymce.PluginManager.add('RMFtooltip', tinymce.plugins.RMFtooltip);
})();
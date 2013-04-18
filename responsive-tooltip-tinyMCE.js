(function() {
	tinymce.create('tinymce.plugins.RMFtooltip', {
		init : function(ed, url) {
			ed.addButton('RMFtooltip', {
				title : 'ToolTip',
				image : url+'/RMFtooltipbutton.png',
				onclick : function() {
					i = jQuery('<div title="Create your tooltip" ></div>');
					if (window.RMFtooltip_cache) {
						i.html(window.RMFtooltip_cache);
					} else {
						jQuery.get(url+'/ajax/form.html', function(data) {
							window.RMFtooltip_cache = data;
							i.html(window.RMFtooltip_cache);
						});
					}
					i.dialog({
						autoOpen: true,
						draggable: false,
						resizable: false,
						modal: true,
						buttons: {
							"OK": function() {
								RMFtooltip_text = jQuery("#RMFtooltip_text").val();
								RMFtooltip_tip = jQuery("#RMFtooltip_tip").val();
								if (RMFtooltip_text != null && RMFtooltip_text != '' && RMFtooltip_tip != null && RMFtooltip_tip != ''){
									ed.execCommand('mceInsertContent', false, '[tooltip tip="'+RMFtooltip_tip+'"]'+RMFtooltip_text+'[/tooltip]');
								}
								jQuery( this ).dialog( "close" );
								jQuery(this).empty();
							},
							Cancel: function() {
								jQuery( this ).dialog( "destroy" );
								jQuery(this).empty();
							}
						}
					});
				}
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
				version : "1.0"
			};
		}
	});
	tinymce.PluginManager.add('RMFtooltip', tinymce.plugins.RMFtooltip);
})();
jQuery(function($) {
	$('.page-title-action').remove();
	$('.adp-toggle-module').on('click', function() {
		var data = {
			action: 'adp_toggle_module',
			module: $(this).data('slug'),
			wpnonce: $(this).data('nonce'),
		};
		var self = $(this);
		var state = $(this).data('state');
		if( !state ) {
			var ckeched = $(this).closest('div').find('input').is(':checked');
			if( !ckeched ) {
				// вывести сообщение о том, что нужно поставить галку
				return false;
			}
		}
		var loader = $(this).closest('div').find('.adp-loader');
		$(this).hide();
		loader.show();

		$.post(ajaxurl, data, function(response) {
			var notice = $('.adp-modules-notice');

			notice.removeClass('notice-success').removeClass('notice-error');

			if( !response || !response.success || !response.data ) {
				notice.addClass('notice-error').show().html('<p>' + response + '</p>');
				return;
			}

			if( response.success ) {
				notice.addClass('notice-success');
			} else {
				notice.addClass('notice-error');
			}
			if( response.data.active ) {
				self.removeClass('adp-button--blue').addClass('adp-button--green').text(self.data('deactivate'));
				self.data('state', 1);
			} else {
				self.removeClass('adp-button--green').addClass('adp-button--blue').text(self.data('activate'));
				self.closest('div').find('input').removeAttr('checked');
				self.data('state', 0);
			}

			notice.show().html('<p>' + response.data.msg + '</p>');
			loader.hide();
			self.show();
		});
	});

	$('.adp-add-new-ads').on('click', function(){
		window.location.href = $(this).attr('href');
	});

	$('.adp-checkbox-tos').on('click', function() {
		var state = $(this).closest('.columns').find('.adp-toggle-module').data('state');
		if( state ) {
			return false;
		}
		var loader = $(this).closest('.columns').find('.adp-loader');
		if( loader.is(':visible') ) {
			return false;
		}
	});
});

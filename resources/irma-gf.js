(function ($) {
	$(document).on('gform_post_render', function () {
		$('.gf_irma_qr').click(function () {
			var id = $(this).attr('data-id');
			var formId = $(this).attr('data-form-id');

			fetch(irma_gf.session_url + '?id=' + formId)
				.then(function (response) { return response.json() })
				.then(function (response) {
					var session = response;
					var qrCanvas = $('#gf_irma_qr_' + id);
					$('#input_' + formId + '_irma_session_token').val(session.token);
					qrCanvas.css({ display: 'block' });

					irma.handleSession(session.sessionPtr, {
						method: 'canvas',
						element: 'gf_irma_qr_' + id
					})
						.then(function () {
							var data = new FormData();
							data.append('token', session.token);
							data.append('formId', formId);

							return fetch(irma_gf.handle_url, {
								method: 'POST',
								body: data,
							});
						})
						.then(function (response) { return response.json() })
						.then(function (response) {
							response.forEach(function (item) {
								$('#' + item.input).val(item.value);
							});
						})
						.catch(function (response) {
							console.warn(response);
						});
				});
		});
	});

})(jQuery);

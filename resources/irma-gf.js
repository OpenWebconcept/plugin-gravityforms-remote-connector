(function ($) {
	$(document).on('gform_post_render', function () {
		$('.gf_irma_qr').click(function () {
			var id = $(this).attr('data-id');
			var formId = $(this).attr('data-form-id');
			var session = irma_gf['form_' + formId];
			var qrCanvas = $('#gf_irma_qr_' + id);

			qrCanvas.css({ display: 'block' });

			irma.handleSession(session.sessionPtr, {
				method: 'canvas',
				element: 'gf_irma_qr_' + id
			})
				.then(function () {
					var data = new FormData();
					data.append('token', session.token);

					return fetch(irma_gf.handle_url, {
						method: 'POST',
						body: data,
					});
				})
				.then(function (response) { return response.json() })
				.then(function (response) {
					var results = $('#gf_irma_results_' + id);
					var resultsTable = results.find('table');

					resultsTable.prepend("<div class=\"alert alert-success\">IRMA attributen zijn succesvol opgehaald!</div>");

					response.forEach(function (item) {
						resultsTable.append("<tr><td>" + item.label + "</td><td>" + item.value + "</td></tr>");
					});

					results.css({ height: 'auto' });
					var height = results.height();
					results.css({ height: 0 });

					qrCanvas.hide();
					results.animate({ height: height });
				});
		});
	});

})(jQuery);

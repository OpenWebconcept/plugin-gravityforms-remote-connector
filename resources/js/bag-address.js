jQuery(document).ready( function() {

    jQuery('#bag-lookup').on('click', function(e) {
        var button = jQuery(this);
        var isValid = true;
        var inputs = jQuery('.gform_wrapper input').filter(function() {
            return this.name.match(/(zip|home-number)$/)
        });

		var isValid = inputs.each(function(index, item) {
            if ( !jQuery(item).val().trim() ) {
                isValid = false;
            }
        });
        if ( !isValid ) {
            return;
        }

        e.preventDefault();

        jQuery.ajax({
            type : 'post',
            dataType : 'json',
            url : bag_address.ajaxurl,
            data: {
				'action': 'bag_address_lookup',
				'zip': document.querySelector("input[data-name='zip']").value,
				'homeNumber': document.querySelector("input[data-name='home-number']").value,
				'homeNumberAddition': document.querySelector("input[data-name='home-number-addition']").value
			},
            beforeSend : function ( xhr ) {
                button.val('Zoekende...').prop('disabled', 'disable');
            },
            success: function(response) {
                if(true === response.success) {
                    document.querySelector("input[data-name='address']").setAttribute('value', response.data.results.street);
                    document.querySelector("input[data-name='city']").setAttribute('value', response.data.results.city);
                    document.querySelector("input[data-name='state']").setAttribute('value', response.data.results.state);
					jQuery('.result').html();
                } else {
					jQuery('.result').html(response.data.message);
                }
            },
			complete: function() {
				button.val('Opzoeken').prop('disabled', false);
			}
        })
    });
})

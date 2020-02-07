<?php

namespace Yard\OpenZaak\Settings\Fields;

use Yard\OpenZaak\AttributesManager;
use Yard\OpenZaak\KeyValuePair;

class ListField
{
    /**
     * Item
     *
     * @var array[]
     */
    protected $item;

    /**
     * Data
     *
     * @var array[]
     */
    protected $data;

    public function __construct(array $item = [])
    {
        $this->item = $item;
        $this->data = AttributesManager::make()->get('attributes');
    }

    /**
     * Render the field.
     *
     * @return void
     */
    public function render(): void
    { ?>
<table>
	<tr>
	<th>Naam ter herkenning</th>
	<th>Attribuut naam van OpenZaak</th>
	</tr>
	<?php foreach ($this->data as $key => $data) { ?>
	<tr>
		<?php $data = KeyValuePair::make($data); ?>
		<td><em><?php echo $data->key(); ?>
		</td>
		<td><em><?php echo $data->value(); ?></em></td>
		<td> &nbsp; &nbsp; &nbsp;<a class="openzaak_remove_attribute button-secondary" data-attribute-key="<?php echo $data->key(); ?>">verwijder</a></td>
	</tr>
	<?php } ?>
</table>
<script>
	(function($) {
		$(document).ready(function() {
			$('.openzaak_remove_attribute').on('click', function(e) {
				e.preventDefault();
				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					method: 'POST',
					data: {
						action: 'openzaak_remove_attribute',
						security: '<?php echo wp_create_nonce('openzaak_remove_attribute'); ?>',
						data: {
							attribute: $(this).attr("data-attribute-key")
						}
					}
				}).done(function(response) {
					location.reload();
				});
			});
		});
	})(jQuery);

</script>
<?php
}
}

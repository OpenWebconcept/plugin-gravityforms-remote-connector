<?php
use Yard\OpenZaak\KeyValuePair;

?>
<li style="display: list-item;">
	<label for="case_property" class="section_label"><?php _e('Case eigenschap', 'irma'); ?></label>
	<select id="case_property" onchange="SetFieldProperty('casePropertyName', this.value);" class="fieldwidth-3">
		<option value=""><?php _e('Kies attribuut', 'irma'); ?>
		</option>
		<?php
                foreach ($attributes->all() as $caseProperty) {
                    $caseProperty = KeyValuePair::make($caseProperty);
                    echo '<option value="'.$caseProperty->key().'">'. $caseProperty->key() .' - '. $caseProperty->value() .'</option>';
                } ?>
	</select>
</li>

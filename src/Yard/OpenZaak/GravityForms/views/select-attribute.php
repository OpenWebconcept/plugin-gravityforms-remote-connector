<?php
use Yard\OpenZaak\KeyValuePair;

?>
<li style="display: list-item;">
	<label for="case_property" class="section_label"><?php _e('Case property', config('core.text_domain')); ?></label>
	<select id="case_property" onchange="SetFieldProperty('casePropertyName', this.value);" class="fieldwidth-3">
		<option value=""><?php _e('Choose attribute', config('core.text_domain')); ?>
		</option>
		<?php
                foreach ($attributes->all() as $caseProperty) {
                    $caseProperty = KeyValuePair::make($caseProperty);
                    echo '<option value="'.$caseProperty->key().'">'. $caseProperty->key() .' - '. $caseProperty->value() .'</option>';
                } ?>
	</select>
</li>

<?php
/**
 * @var int
 * @var int    $id
 * @var string $value
 * @var string $placeholder
 */
?>
<div class="ginput_container ginput_container_irma_attribute">
	<input type="text" id="<?php echo esc_attr($fieldId); ?>" name="input_<?php echo $id; ?>" value="<?php echo $value; ?>" <?php echo $placeholder; ?> />
</div>

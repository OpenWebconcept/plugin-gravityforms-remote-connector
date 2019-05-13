<?php
/**
 * @var \IRMA\WP\GravityForms\IrmaLaunchQR $this
 * @var int $id
 * @var int $formId
 * @var string $buttonLabel
 */
?>
<div class="ginput_container ginput_container_irma_qr" id="gf_irma_container_<?php echo $id; ?>">
	<input type="button" value="<?php echo $buttonLabel; ?>" <?php echo $this->get_tabindex(); ?> class="btn btn-secondary gf_irma_qr" data-id="<?php echo $id; ?>" data-form-id="<?php echo $formId; ?>" data-popup="<?php echo $popup; ?>" />
	<canvas id="gf_irma_qr_<?php echo $id; ?>" class="gf_irma_qr_canvas"></canvas>
	<input type="hidden" name="input_<?php echo $formId; ?>_irma_session_token" id="input_<?php echo $formId; ?>_irma_session_token" />
</div>

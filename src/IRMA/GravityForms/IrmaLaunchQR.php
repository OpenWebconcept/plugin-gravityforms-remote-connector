<?php

namespace IRMA\WP\GravityForms;

use GF_Field;

class IrmaLaunchQR extends GF_Field
{
    public $type = 'IRMA-launch-QR';

    /**
     * Renders the field.
     *
     * @param string $form
     * @param string $value
     * @param object $entry
     *
     * @return void
     */
    public function get_field_input($form, $value = '', $entry = null)
    {
        $form_id         = $form['id'];
        $is_entry_detail = $this->is_entry_detail();
        $id              = (int) $this->id;

        $input = '<div class="ginput_container" id="gf_irma_container_'.$id.'">' .
             '<input type="button" value="Haal IRMA attributen op" ' . $this->get_tabindex() .' class="btn btn-secondary gf_irma_qr" data-id="'.$id.'" data-form-id="'.$form_id.'">' .
             '<canvas id="gf_irma_qr_'.$id.'" class="gf_irma_qr_canvas"></canvas>'.
             '<div id="gf_irma_results_'.$id.'" class="gf_irma_results">
				 <div class="irma_wp_card">
					<table>
						<tr>
							<th>Attribuut</th>
							<th>Waarde</th>
						</tr>
					</table>
				 </div>
			 </div>'
            . "</div>";

        return $input;
    }

    public function validate($value, $form)
    {
        $this->failed_validation = false;
    }

    public function get_form_editor_button()
    {
        return [
            'group' => 'advanced_fields',
            'text'  => $this->get_form_editor_field_title(),
        ];
    }
}

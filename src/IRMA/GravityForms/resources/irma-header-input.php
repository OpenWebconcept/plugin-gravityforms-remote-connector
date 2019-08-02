<?php

/**
 * @var int
 * @var int    $id
 * @var string $value
 * @var string $placeholder
 * @var string $logoUrl
 */
?>
<div class="ginput_container ginput_container_irma_header">
  <nav class="navbar navbar-light bg-light">
    <img alt="irma-logo-new" class="irma-logo" src="<?php echo $logoUrl; ?>" />
    <div id="irma-login-text"><?php echo $text; ?></div>
    <button <?php echo $this->get_tabindex(); ?> class="btn btn-success gf_irma_qr" data-id="<?php echo $id; ?>" data-form-id="<?php echo $formId; ?>" data-popup="<?php echo $popup; ?>"><i class="fa fa-fw fa-user"></i><?php echo $buttonLabel; ?></button>
    <canvas id="gf_irma_qr_<?php echo $id; ?>" class="gf_irma_qr_canvas"></canvas>
    <input type="hidden" name="input_<?php echo $formId; ?>_irma_session_token" id="input_<?php echo $formId; ?>_irma_session_token" />
  </nav>
</div>

<?php
// Retrieve the session token from the form data.
$sessionToken = $this->get_input_value_submission('input_' . $formId . '_irma_session_token');
$groundTruth = get_transient('irma_result_' . $sessionToken);
?>

<script>
  document.getElementsByTagName("body")[0].onload = function() {
    // convert PHP variables into JS variables
    let attributeFullnameID = "<?php echo $irmaHeaderAttributeFullnameID ?>";
    let attributeBsnID = "<?php echo $irmaHeaderAttributeBsnID ?>";

    //detect if BSN has changed
    bsn = document.getElementById(attributeBsnID);
    oldval = newval = bsn.value;

    setInterval(function() {
      newval = bsn.value
      if (oldval !== newval) {
        if (newval !== localStorage.getItem(attributeBsnID)) {
          txt = "Uw BSN is verplicht bij inloggen";
          document.getElementById("irma-login-text").innerHTML = txt;
          oldval = newval;

          return;
        }

        txt = "Ingelogd als " + document.getElementById(attributeFullnameID).value;
        txt += " (BSN " + newval + ")";
        document.getElementById("irma-login-text").innerHTML = txt;

        oldval = newval;

        return
      }
    }, 100);

  };
</script>
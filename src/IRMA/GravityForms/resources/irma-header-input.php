<?php

/**
 * @var int    $id
 * @var string $value
 * @var string $placeholder
 * @var string $logoUrl
 * @var string $attributeFullnameID
 * @var string $attributeBsnID
 */
?>
<div class="ginput_container ginput_container_irma_header">
  <input type="hidden" id="<?php echo esc_attr($fieldId); ?>" name="input_<?php echo $id; ?>" value="<?php echo $value; ?>" />
  <nav class="navbar navbar-light bg-light">
    <img alt="irma-logo-new" class="irma-logo" src="<?php echo $logoUrl; ?>" />
    <div id="irma-login-text"><?php echo $text; ?></div>
    <input type="button" id="irma-irma-log-in" <?php echo $this->get_tabindex(); ?> class="btn btn-success gf_irma_qr" data-id="<?php echo $id; ?>" data-form-id="<?php echo $formId; ?>" data-popup="<?php echo $popup; ?>" value="<?php echo $buttonLabel; ?>" />
    <canvas id="gf_irma_qr_<?php echo $id; ?>" class="gf_irma_qr_canvas"></canvas>
    <input type="button" id="irma-log-out" class="btn btn-success d-none" onclick="logOut()" value="Uitloggen" />
    <input type="hidden" name="input_<?php echo $formId; ?>_irma_session_token" id="input_<?php echo $formId; ?>_irma_session_token" />
  </nav>
</div>

<script>
  document.getElementsByTagName("body")[0].onload = function() {
    setInterval(function() {
      if (sessionStorage.getItem('startIRMA')) {
        if (sessionStorage.getItem(irma_header.irmaHeaderAttributeFullnameID) && sessionStorage.getItem(irma_header.irmaHeaderAttributeBsnID)) {
          txt = "Ingelogd als " + sessionStorage.getItem(irma_header.irmaHeaderAttributeFullnameID);
          txt += " (BSN " + sessionStorage.getItem(irma_header.irmaHeaderAttributeBsnID) + ")";
          document.getElementById("irma-login-text").innerHTML = txt;
          document.getElementById("irma-irma-log-in").classList.add("d-none");
          document.getElementById("irma-log-out").classList.remove("d-none");
        }

        if (!sessionStorage.getItem(irma_header.irmaHeaderAttributeBsnID)) {
          document.getElementById("irma-login-text").innerHTML = 'Uw BSN is verplicht bij het inloggen';
          document.getElementById("irma-irma-log-in").classList.add("d-none");
          document.getElementById("irma-log-out").classList.remove("d-none");
        }

        if (!sessionStorage.getItem(irma_header.irmaHeaderAttributeFullnameID) && !sessionStorage.getItem(irma_header.irmaHeaderAttributeBsnID)) {
          document.getElementById("irma-login-text").innerHTML = irma_header.text;
          document.getElementById("irma-irma-log-in").classList.remove("d-none");
          document.getElementById("irma-log-out").classList.add("d-none");
        }
      }
    }, 100);
  };

  function logOut() {
    sessionStorage.clear();
    window.location.assign(location.href);
  }
</script>
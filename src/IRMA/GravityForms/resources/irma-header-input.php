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
  <nav class="irma-navbar">
    <img alt="irma-logo-new" class="irma-logo" src="<?php echo $logoUrl; ?>" />
    <div id="irma-login-text"><?php echo $text; ?></div>
    <input type="button" id="irma-irma-log-in" <?php echo $this->get_tabindex(); ?> class="irma-btn irma-btn-succes gf_irma_qr" data-id="<?php echo $id; ?>" data-form-id="<?php echo $formId; ?>" data-popup="<?php echo $popup; ?>" value="<?php echo $buttonLabel; ?>" />
    <canvas id="gf_irma_qr_<?php echo $id; ?>" class="gf_irma_qr_canvas"></canvas>
    <input type="button" id="irma-log-out" class="irma-btn irma-btn-secondary d-none" onclick="logOut('<?php echo $link; ?>')" value="Uitloggen" />
    <input type="hidden" name="input_<?php echo $formId; ?>_irma_session_token" id="input_<?php echo $formId; ?>_irma_session_token" />
  </nav>
</div>

<script>
  let irmaHeaderAttributeFullnameID = "<?php echo $irmaHeaderAttributeFullnameID; ?>";
  let irmaHeaderAttributeBsnID = "<?php echo $irmaHeaderAttributeBsnID; ?>";
  let irmaHeaderAttributeCity = "<?php echo $irmaHeaderAttributeCity; ?>";
  let irmaHeaderCity = "<?php echo $irmaHeaderCity; ?>";
  let text = "<?php echo $text; ?>";
  let fieldId = "<?php echo esc_attr($fieldId); ?>";
  let formId = "<?php echo $formId; ?>";
  let id = "<?php echo $id; ?>";
  let link = "<?php echo $link; ?>";

  document.getElementsByTagName("body")[0].onload = function() {
    setInterval(function() {
      document.getElementById("field_" + formId + "_" + id).classList.remove("gfield_error");
      if (sessionStorage.getItem('startIRMA')) {
        document.getElementById('input_' + formId + '_irma_session_token').value = sessionStorage.getItem('startIRMA');

        // if the BSN attribute is not present
        checkSessionBSN(irmaHeaderAttributeBsnID, fieldId);

        // if the attribute city is not equal to the city that is used for validation
        checkSessionCity(irmaHeaderAttributeCity, irmaHeaderCity, irmaHeaderAttributeBsnID)

        // if the attributes BSN and fullname are not present
        checkFullnameBSN(irmaHeaderAttributeFullnameID, irmaHeaderAttributeBsnID, text)

        // if all the attributes are present
        checkAllAttributes(irmaHeaderAttributeFullnameID, irmaHeaderAttributeBsnID, irmaHeaderAttributeCity, irmaHeaderCity, fieldId)
      }
    }, 100);
  };

  function checkSessionBSN(irmaHeaderAttributeBsnID, fieldId) {
    if (!sessionStorage.getItem(irmaHeaderAttributeBsnID)) {
      document.getElementById("irma-login-text").innerHTML = 'Uw BSN is verplicht bij het inloggen';
      document.getElementById("irma-login-text").classList.add("alert");
      document.getElementById("irma-login-text").classList.add("alert-danger");
      document.getElementById("irma-irma-log-in").classList.remove("d-none");
      document.getElementById("irma-log-out").classList.add("d-none");
      clearFieldBSN(fieldId);
    }
  }

  function checkSessionCity(irmaHeaderAttributeCity, irmaHeaderCity, irmaHeaderAttributeBsnID) {
    if (irmaHeaderAttributeCity && irmaHeaderCity && sessionStorage.getItem(irmaHeaderAttributeCity) !== irmaHeaderCity && sessionStorage.getItem(irmaHeaderAttributeBsnID)) {
      txt = 'Deze dienst is alleen beschikbaar voor inwoners van de stad ' + irmaHeaderCity;
      document.getElementById("irma-login-text").innerHTML = txt;
      document.getElementById("irma-login-text").classList.add("alert");
      document.getElementById("irma-login-text").classList.add("alert-warning");
      clearFieldBSN(fieldId);
      document.getElementById("irma-irma-log-in").classList.add("d-none");
      document.getElementById("irma-log-out").classList.remove("d-none");
    }
  }

  function checkFullnameBSN(irmaHeaderAttributeFullnameID, irmaHeaderAttributeBsnID, text) {
    if (!sessionStorage.getItem(irmaHeaderAttributeFullnameID) && !sessionStorage.getItem(irmaHeaderAttributeBsnID)) {
      document.getElementById("irma-login-text").innerHTML = text; // initial content
      document.getElementById("irma-irma-log-in").classList.remove("d-none");
      document.getElementById("irma-log-out").classList.add("d-none");
      document.getElementById("irma-login-text").classList.add("alert");
      document.getElementById("irma-login-text").classList.add("alert-danger");
      document.getElementById("irma-login-text").classList.remove("alert-warning");
    }
  }

  // ook checken of er een woonplaats check is ingevuld.
  function checkAllAttributes(irmaHeaderAttributeFullnameID, irmaHeaderAttributeBsnID, irmaHeaderAttributeCity, irmaHeaderCity, fieldId) {
    if (irmaHeaderAttributeCity && irmaHeaderCity) {
      if (sessionStorage.getItem(irmaHeaderAttributeFullnameID) && sessionStorage.getItem(irmaHeaderAttributeBsnID) && sessionStorage.getItem(irmaHeaderAttributeCity) === irmaHeaderCity) {
        document.getElementById(fieldId).value = sessionStorage.getItem(irmaHeaderAttributeBsnID);
        txt = "Ingelogd als " + sessionStorage.getItem(irmaHeaderAttributeFullnameID);
        txt += " (BSN " + sessionStorage.getItem(irmaHeaderAttributeBsnID) + ")";
        document.getElementById("irma-login-text").innerHTML = txt;
        document.getElementById("irma-irma-log-in").classList.add("d-none");
        document.getElementById("irma-log-out").classList.remove("d-none");
        document.getElementById("irma-login-text").classList.remove("alert");
        document.getElementById("irma-login-text").classList.remove("alert-danger");
        document.getElementById("irma-login-text").classList.remove("alert-warning");
      }
    }

    if (!irmaHeaderAttributeCity || !irmaHeaderCity) {
      if (sessionStorage.getItem(irmaHeaderAttributeFullnameID) && sessionStorage.getItem(irmaHeaderAttributeBsnID)) {
        document.getElementById(fieldId).value = sessionStorage.getItem(irmaHeaderAttributeBsnID);
        txt = "Ingelogd als " + sessionStorage.getItem(irmaHeaderAttributeFullnameID);
        txt += " (BSN " + sessionStorage.getItem(irmaHeaderAttributeBsnID) + ")";
        document.getElementById("irma-login-text").innerHTML = txt;
        document.getElementById("irma-irma-log-in").classList.add("d-none");
        document.getElementById("irma-log-out").classList.remove("d-none");
        document.getElementById("irma-login-text").classList.remove("alert");
        document.getElementById("irma-login-text").classList.remove("alert-danger");
        document.getElementById("irma-login-text").classList.remove("alert-warning");
      }
    }
  }

  function logOut(link) {
    sessionStorage.clear();
    window.location.assign(link);
  }
</script>
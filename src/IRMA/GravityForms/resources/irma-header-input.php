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
    <!-- <a class="navbar-brand" href="#"> -->
    <img alt="irma-logo-new" class="irma-logo" src="<?php echo $logoUrl; ?>" />
    <!-- </a> -->
    <div id="irma-login-text"><?php echo $irmaHeaderText; ?></div>
    <button <?php echo $this->get_tabindex(); ?> class="btn btn-success gf_irma_qr" data-id="<?php echo $id; ?>" data-form-id="<?php echo $formId; ?>" data-popup="<?php echo $popup; ?>"><i class="fa fa-fw fa-user"></i><?php echo $irmaHeaderButtonLabel; ?></button>
  </nav>
</div>

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
        txt = "Ingelogd als " + document.getElementById(attributeFullnameID).value;
        txt += " (BSN " + newval + ")";
        document.getElementById("irma-login-text").innerHTML = txt;

        oldval = newval;
      }
    }, 100);

  };
</script>
<?php wp_head(); ?>

<?php

global $wp;
$link = home_url($wp->request);

?>
<div style="margin:auto;">
    <p>
        Something went wrong while requesting the API. Go back to the form by following <a href="<?php echo $link; ?>">this</a> link.
    </p>
</div>

<?php wp_footer(); ?>
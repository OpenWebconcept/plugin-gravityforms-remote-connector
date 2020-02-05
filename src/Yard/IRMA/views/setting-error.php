<?php
/**
 * @var UnexpectedValueException $e;
 */

// wp_head();
?>

<div style="margin:auto;">
	<p>
		Something went wrong while requesting the API. Go back to the form by following <a href="<?php echo site_url('/'); ?>">this</a> link.<br />
		Error: <?php echo $e->getMessage(); ?>
	</p>
</div>

<?php //wp_footer();

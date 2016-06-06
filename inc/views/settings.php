<?php do_action( 'sunrise_page_before' ); ?>
<div class="gpr-about-wrap"></div>
<a href="http://wordimpress.com/plugins/google-places-reviews-pro/" class="new-window upgrade-link"><?php _e( 'Upgrade to Pro', 'google-places-reviews' ); ?></a>
<p class="label">Free Version <?php echo $this->version; ?></p>

<div id="sunrise-plugin-settings" class="wrap">
	<h1 id="sunrise-plugin-tabs" class="nav-tab-wrapper hide-if-no-js">
		<?php
		// Show tabs
		$this->render_tabs();
		?>
	</h1>
	<?php
	// Show notifications
	$this->notifications(
		array(
			'js'          => __( 'For full functionality of this page it is recommended to enable javascript.', 'google-places-reviews' ),
			'reseted'     => __( 'Settings Reset Successfully', 'google-places-reviews' ),
			'not-reseted' => __( 'Plugins already set to default settings', 'google-places-reviews' ),
			'saved'       => __( 'Settings saved successfully', 'google-places-reviews' ),
			'not-saved'   => __( 'Settings not saved due to no changes being made.', 'google-places-reviews' )
		)
	);
	?>
	<form action="<?php echo $this->admin_url; ?>" method="post" id="sunrise-plugin-options-form">
		<?php
		// Show options
		$this->render_panes();
		?>
		<input type="hidden" name="action" value="save"/>
	</form>


</div>
<?php do_action( 'sunrise_page_after' ); ?>

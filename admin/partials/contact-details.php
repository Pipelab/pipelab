<?php
$contact_id = filter_input( INPUT_GET, 'contact_id', FILTER_SANITIZE_NUMBER_INT );

// Make sure that we have a contact ID to use.
if ( empty( $contact_id ) ) {
	_e( '<p>This contact could not be found.</p>', 'pipelab' );
	return;
}

try {
	$contact = new \Pipelab\Contact( $contact_id );
} catch ( Exception $e ) {
	_e( '<p>This contact could not be found.</p>', 'pipelab' );
	return;
}
?>
<div class="wrap">

	<h2><?php echo $contact->get_full_name(); ?></h2>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-1">
			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<button type="button" class="handlediv" aria-expanded="true" >
							<span class="screen-reader-text">Toggle panel</span>
							<span class="toggle-indicator" aria-hidden="true"></span>
						</button>
						<!-- Toggle -->
						<h2 class="handle">
							<span><?php esc_attr_e( 'Contact Information', 'pipelab' ); ?></span>
						</h2>

						<div class="inside">
						</div>
						<!-- .inside -->

					</div>
					<!-- .postbox -->

				</div>
				<!-- .meta-box-sortables .ui-sortable -->

			</div>
			<!-- post-body-content -->



		</div>
		<!-- #post-body .metabox-holder .columns-2 -->

		<br class="clear">
	</div>
	<!-- #poststuff -->
</div>

<div class="wrap">
	<h2><?php _e('Contacts', 'pipelab' ); ?></h2>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post">
						<?php
						pipelab()->plugin_admin->contacts_list->prepare_items();
						pipelab()->plugin_admin->contacts_list->display(); ?>
					</form>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>
</div>

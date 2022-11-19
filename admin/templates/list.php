<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
	<form id="application-filter" method="post">
		<!-- For plugins, we also need to ensure that the form posts back to our current page -->
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
		<!-- Now we can render the completed list table -->
		<?php
		$list->search_box( 'search', 'search_id' );
		$list->display();
		?>
	</form>

</div>
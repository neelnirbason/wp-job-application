<table class="wp-list-table widefat fixed striped table-view-list applications">
    <thead>
    <tr>
        <th scope="col" id="name" class="manage-column column-name column-primary">Name</th>
        <th scope="col" id="post" class="manage-column column-post">Post Name</th>
        <th scope="col" id="attachment" class="manage-column column-attachment">CV</th>
    </tr>
    </thead>
    <tbody id="the-list" data-wp-lists="list:application">
	<?php if ( count( $applicants ) > 0 ): ?>
		<?php foreach ( $applicants as $applicant ): ?>
            <tr>
                <td class="name column-name has-row-actions column-primary"><?php echo "{$applicant['first_name']} {$applicant['last_name']}" ?></td>
                <td class="post column-post"><?php echo $applicant['email'] ?></td>
                <td class="attachment column-attachment">
                    <a href="<?php echo wp_get_attachment_url( $applicant['attachment_id'] ) ?>" target="_blank">View</a>
                </td>
            </tr>
		<?php endforeach; ?>
	<?php else: ?>
        <tr>
            <td class="name column-name has-row-actions column-primary"><?php __( "No one applied yet!", 'wp-job-application' ) ?></td>
        </tr>
	<?php endif; ?>
    </tbody>
    <tfoot>
</table>
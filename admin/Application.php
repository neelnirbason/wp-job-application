<?php
/**
 * Handles Application related tasks for admin panel
 *
 * @package    DevKabir\Admin
 * @since      1.0.0
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */

namespace DevKabir\Admin;

use DevKabir\Application\Loader;


/**
 * Will handle,
 * 1. Add admin page for applications.
 * 2. Generates content for application page.
 * 3. Create connection between application list table and database.
 *
 * @subpackage DevKabir\Admin\Application
 * @since      1.0.0
 */
class Application {
	/**
	 * Submissions constructor.
	 *
	 * @param Loader $loader Action and filter register.
	 */
	public function __construct( Loader $loader ) {
		$loader->add_filter( 'admin_menu', [ $this, 'register' ] );
	}

	/**
	 * Get all submissions from database.
	 *
	 * @param null|string $query Search query made by the admin.
	 * @param int         $limit number of application should be return.
	 *
	 * @return array Applications
	 * @since 1.0.0
	 */
	final public static function get( string $query = null, int $limit = 0 ): array {
		global $wpdb;

		$table = $wpdb->prefix . 'applicant_submissions';
		$sql   = 'SELECT * FROM ' . $table;
		if ( ! empty( $query ) ) {
			$query = sanitize_title_for_query( $query );
			$query = '%' . $wpdb->esc_like( $query ) . '%';
			$sql   .= $wpdb->prepare(
				' Where `first_name` LIKE %s
					OR `last_name` LIKE %s
 					OR `address` LIKE %s
					OR `email` LIKE %s
					OR `phone` LIKE %s
 					OR `post` LIKE %s
					OR `attachment_id` LIKE %s
					OR CAST(`submission_date` AS CHAR) LIKE %s',
				$query,
				$query,
				$query,
				$query,
				$query,
				$query,
				$query,
				$query
			);
		}
		$sql .= ' ORDER BY id DESC';

		if ( $limit > 0 ) {
			$sql .= $wpdb->prepare( ' LIMIT %d', $limit );
		}

		return $wpdb->get_results( $sql, ARRAY_A );
	}

	/**
	 * Register before the administration menu loads in the admin.
	 *
	 * @since    1.0.0
	 */
	final public function register(): void {
		add_menu_page(
			__( 'Job Application', 'wp-job-application' ),
			__( 'Job Application', 'wp-job-application' ),
			'manage_options',
			'job-page',
			[ $this, 'render' ],
			'dashicons-id-alt',
		);
	}

	/**
	 * Render html for the menu page
	 */
	final public function render(): void {
		$list = new ApplicationList();
		$list->prepare_items();
		include dirname( __FILE__ ) . '/templates/list.php';
	}
}

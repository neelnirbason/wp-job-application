<?php

namespace DevKabir\Admin;

use DevKabir\Application\Loader;

/**
 * Class Submissions will handle submissions related tasks
 *
 * @property string name    Unique identifier of the plugin
 * @property string version Current Version of the plugin
 * @package DevKabir\Admin
 * @since   1.0.0
 */
class Submissions {
	/**
	 * Submissions constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct( Loader $loader ) {
		$loader->add_filter( 'admin_menu', array( $this, 'register' ) );
	}

	/**
	 * Get all submissions from database.
	 *
	 * @param null|string $query Search query made by the admin
	 * @param int         $limit number of application should be return
	 *
	 * @return array Applications
	 * @since 1.0.0
	 */
	final public static function get( string $query = null, int $limit = 0 ): array {
		global $wpdb;

		$table = $wpdb->prefix . WJA_TABLE;
		$sql   = 'SELECT * FROM ' . $table;
		if ( ! empty( $query ) ) {
			$query = sanitize_title_for_query( $query );
			$query = '%' . $wpdb->esc_like( $query ) . '%';
			$sql  .= $wpdb->prepare(
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
	 * register before the administration menu loads in the admin.
	 *
	 * @since    1.0.0
	 */
	final public function register(): void {
		add_menu_page(
			__( 'Job Application', WJA_NAME ),
			__( 'Job Application', WJA_NAME ),
			'manage_options',
			'job-page',
			array( $this, 'render' ),
			'dashicons-id-alt',
		);
	}

	/**
	 * Render html for the menu page
	 */
	final public function render(): void {
		$list = new Submission_List();
		$list->prepare_items();
		include dirname( __FILE__ ) . '/templates/list.php';
	}
}

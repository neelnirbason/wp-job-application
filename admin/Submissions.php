<?php


namespace DevKabir\Admin;


use DevKabir\Application\Loader;

/**
 * Class Submissions will handle submissions related tasks
 *
 * @property string name
 * @property string version
 * @property string table
 * @package DevKabir\Admin
 */
class Submissions {

	/**
	 * Submissions constructor.
	 *
	 * @param Loader $loader
	 */
	public function __construct( Loader $loader ) {
		$this->name    = $loader->get_name();
		$this->version = $loader->get_version();
		$loader->add_filter( 'admin_menu', [ $this, 'register' ] );
	}

	final public static function get( string $query = null, int $limit = 0 ): array {
		global $wpdb;

		$table = $wpdb->prefix . 'applicant_submissions';
		$sql   = "SELECT * FROM $table";
		if ( ! empty( $query ) ) {
			$query = sanitize_title_for_query( $query );
			$sql   .= " Where `first_name` LIKE '%$query%'
					OR `last_name` LIKE '%$query%'
 					OR `address` LIKE '%$query%'
					OR `email` LIKE '%$query%'
					OR `phone` LIKE '%$query%'
 					OR `post` LIKE '%$query%'
					OR `attachment_id` LIKE '%$query%'
					OR CAST(`submission_date` AS CHAR) LIKE '%$query%'";
		}
		$sql .= " ORDER BY id DESC";

		if ( $limit > 0 ) {
			$sql .= " LIMIT " . $limit;
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
			__( 'Job Application', $this->name ),
			__( 'Job Application', $this->name ),
			'manage_options',
			'job-page',
			[ $this, 'render' ],
			'dashicons-groups'
		);
	}

	final public function render(): void {
		$list = new Submission_List( $this->name );
		$list->prepare_items();
		ob_start();
		include plugin_dir_path( __FILE__ ) . 'templates/list.php';
		echo ob_get_clean();
	}
}
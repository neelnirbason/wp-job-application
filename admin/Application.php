<?php
/**
 * Handles Application related tasks for admin panel
 *
 * @package    DevKabir\Admin
 * @since      1.0.0
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */

namespace DevKabir\Admin;


use DateInterval;
use DevKabir\Application\Loader;
use Exception;

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
		$loader->add_action( 'admin_menu', [ $this, 'register' ] );
		$loader->add_action( 'wp_dashboard_setup', [ $this, 'dashboard_widget' ] );
	}

	/**
	 * Send Applicant notification about successful submission
	 *
	 * @param array $data Application data.
	 */
	final public static function notify( array $data ): bool {
		$logo_url         = plugin_dir_url( __FILE__ ) . '/assets/images/logo.png';
		$hero_image       = plugin_dir_url( __FILE__ ) . '/assets/images/hero.png';
		$orange_image     = plugin_dir_url( __FILE__ ) . '/assets/images/orange.png';
		$grey_image       = plugin_dir_url( __FILE__ ) . '/assets/images/grey.png';
		$company_image    = plugin_dir_url( __FILE__ ) . '/assets/images/company.png';
		$name             = "{$data['first_name']} {$data['last_name']}";
		$shortlist_date   = self::get_date_after( 5 );
		$interview_period = self::get_date_after( 10 ) . '-' . self::get_date_after( 15 );
		$result_date      = self::get_date_after( 20 );
		ob_start();
		include dirname( __FILE__ ) . '/templates/email-new-submission.php';
		$body = ob_get_clean();
		wp_mail( $data['email'], "We have received your application.", $body, [ 'Content-Type: text/html; charset=UTF-8' ] );

		return true;
	}


	/**
	 * Get date after a given duration
	 *
	 * @param int $duration Duration.
	 *
	 * @return string Formatted string representation of date.
	 */
	private static function get_date_after( int $duration ): string {
		try {
			return date_create()->add( new DateInterval( 'P' . $duration . 'D' ) )->format( 'M d' );
		} catch ( Exception $e ) {
			wp_mail( get_option( 'admin_email' ), 'Error:: ' . __FILE__ . ':' . __LINE__, $e->getMessage() );
		}
	}

	/**
	 * Save application in database.
	 *
	 * @param array $submission A application submission.
	 *
	 * @since 1.0.0
	 */
	final public static function store( array $submission ) {
		global $wpdb;

		$table = $wpdb->prefix . 'applicant_submissions';

		return $wpdb->insert(
			$table,
			$submission,
			[ '%s', '%s', '%s', '%s', '%s', '%s', '%d' ]
		);
	}

	/**
	 * Remove application by its id
	 *
	 * @param int $id Identity of the application.
	 *
	 * @return int|false
	 */
	final public static function delete( int $id ) {
		global $wpdb;
		$table = $wpdb->prefix . 'applicant_submissions';

		return $wpdb->delete( $table, [ 'id' => $id ], [ '%d' ] );

	}

	/**
	 * Register before the administration menu loads in the admin.
	 *
	 * @since    1.0.0
	 */
	final public function register(): void {
		wp_enqueue_style( 'wp-job-application-notyf' );
		wp_enqueue_style( 'wp-job-application' );
		wp_enqueue_script( 'wp-job-application-notyf' );
		wp_enqueue_script( 'wp-job-application' );
		add_menu_page(
			__( 'Applications', 'wp-job-application' ),
			__( 'Applications', 'wp-job-application' ),
			'manage_options',
			'applications',
			[ $this, 'render_page' ],
			'dashicons-id-alt',
		);
	}

	/**
	 * Render html for the menu page
	 */
	final public function render_page(): void {
		$list = new ApplicationList();
		$list->prepare_items();
		include dirname( __FILE__ ) . '/templates/list.php';
	}

	/**
	 * A new dashboard widget for latest submissions.
	 *
	 * @return void
	 */
	final public function dashboard_widget(): void {
		wp_add_dashboard_widget(
			'last-five-submission',
			__( 'Latest submission', 'wp-job-application' ),
			[ $this, 'render_widget' ],
			null,
			null,
			'column4',
			'high'
		);
	}

	/**
	 * Render latest application from database in dashboard
	 */
	final public function render_widget() {
		$applicants = self::get( null, 5 );
		include dirname( __FILE__ ) . '/templates/top-five.php';
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
				$query, $query, $query, $query, $query, $query, $query, $query
			);
		}
		$sql .= ' ORDER BY id DESC';

		if ( $limit > 0 ) {
			$sql .= $wpdb->prepare( ' LIMIT %d', $limit );
		}

		return $wpdb->get_results( $sql, ARRAY_A );
	}
}

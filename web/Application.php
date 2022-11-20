<?php
/**
 * Handles application submission from frontend
 *
 * @package    DevKabir\Web
 * @since      1.0.0
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */

namespace DevKabir\Web;


use DevKabir\Application\Loader;

/**
 * Will handle
 * 1. shortcode rendering
 * 2. shortcode ajax request
 * 3. insert a new application into database
 *
 * @subpackage DevKabir\Web\Application
 */
class Application {

	/**
	 * Application constructor.
	 *
	 * @param \DevKabir\Application\Loader $loader register of all hooks and filters.
	 */
	final public function __construct( Loader $loader ) {
		add_shortcode( 'applicant_form', [ $this, 'add' ] );
		$this->render();
	}

	/**
	 * Make a page for the application form
	 */
	final private function render() {
		$slug = 'apply';
		$page = [
			'comment_status' => 'close',
			'ping_status'    => 'close',
			'post_author'    => get_current_user_id(),
			'post_title'     => ucwords( $slug ),
			'post_name'      => $slug,
			'post_status'    => 'publish',
			'post_content'   => '[applicant_form]',
			'post_type'      => 'page',
		];
		if ( ! get_page_by_path( $slug, OBJECT ) ) {
			wp_insert_post( $page );
		}
	}

	/**
	 * Generates content for the application form shortcode
	 */
	final public function add() {
		wp_enqueue_style( 'wp-job-application-shortcode' );
		ob_start();
		include dirname( __FILE__ ) . '/templates/form.php';

		return ob_get_clean();
	}
}

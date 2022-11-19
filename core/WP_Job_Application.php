<?php


namespace DevKabir\Application;

use DevKabir\Admin\Init as Admin;
use DevKabir\Web\Init as Web;


/**
 * Class WP_Job_Application
 * @property Loader loader
 * @package DevKabir\Application
 */
class WP_Job_Application {

	/**
	 * WP_Job_Application constructor.
	 */
	public function __construct() {
		$this->loader = new Loader( 'wp-job-application', '1.0.0' );
		$this->load();
	}

	/**
	 * Load the application based on user interface
	 *
	 * @since 1.0.0
	 */
	final public function load(): void {
		if ( is_admin() ) {
			( new Admin() )->run( $this->loader );
		} else {
			( new Web() )->run( $this->loader );
		}
	}

	/**
	 * Make run loader, which will load all the action and filter based on the current interface
	 */
	final public function start(): void {

		$this->loader->run();

	}


}
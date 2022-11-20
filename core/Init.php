<?php
/**
 * Core Class for this plugin
 *
 * @package    DevKabir\Admin
 * @since      1.0.0
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */

namespace DevKabir\Application;

use DevKabir\Admin\Ajax;
use DevKabir\Admin\Init as Admin;
use DevKabir\Web\Init as Web;

/**
 * Class WPJobApplication
 *
 * @property Loader loader
 * @package  DevKabir\Application
 */
class Init {



	/**
	 * WPJobApplication constructor.
	 */
	public function __construct() {
		$this->loader = new Loader();
		$this->load();
	}


	/**
	 * Load the application based on user interface
	 *
	 * @since 1.0.0
	 */
	final public function load(): void {
		if ( ! is_admin() ) {
			( new Web() )->run( $this->loader );
		} elseif ( ! wp_doing_ajax() ) {
			( new Admin() )->run( $this->loader );
		} else {
			( new Ajax() )->run( $this->loader );
		}
	}

	/**
	 * Make run loader, which will load all the action and filter based on the current interface
	 */
	final public function start(): void {
		$this->loader->run();
	}
}

<?php
/**
 * Backend Administration
 *
 * @since      1.0.0
 * @package    DevKabir\Admin
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */

namespace DevKabir\Admin;


use DevKabir\Application\Loader;

/**
 * Will handle,
 * 1. Load admin scripts
 * 2. Load Application Page
 * 3. Load Widget
 *
 * @subpackage DevKabir\Admin\Init
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */
class Init {

	/**
	 * Ignite this class
	 *
	 * @param \DevKabir\Application\Loader $loader Action and filter register.
	 *
	 * @return void
	 */
	final public function run( Loader $loader ): void {
		$loader->add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		new Application( $loader );
	}

	/**
	 * Register styles and scripts for the admin panel
	 */
	final public function enqueue_scripts(): void {
		wp_enqueue_style( 'wp-job-application', plugin_dir_url( __FILE__ ) . 'assets/styles.css', [], '1.0.0' );
	}
}

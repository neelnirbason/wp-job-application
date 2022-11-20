<?php
/**
 * Frontend administrator
 *
 * @package DevKabir\Web
 * @since   1.0.0
 * @author  Dev Kabir <dev.kabir01@gmail.com>
 */

namespace DevKabir\Web;

use DevKabir\Application\Loader;


/**
 * Will handle all classes related to the frontend
 *
 * @subpackage DevKabir\Web\Init
 */
class Init {

	/**
	 * Frontend related classes will be loaded
	 *
	 * @param \DevKabir\Application\Loader $loader register of all hooks and filters.
	 */
	final public function run( Loader $loader ): void {
		$loader->add_filter( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		new Application( $loader );
	}

	/**
	 * Register styles and scripts for the website
	 */
	final public function enqueue_scripts(): void {
		wp_register_style( 'wp-job-application-shortcode', plugin_dir_url( __FILE__ ) . 'assets/styles.css', [], '1.0.0' );
	}
}

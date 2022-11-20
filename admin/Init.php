<?php /** @noinspection ALL */

/** @noinspection ALL */

namespace DevKabir\Admin;

use DevKabir\Application\Loader;

/**
 * Class Init
 *
 * The admin-specific functionality of the plugin.
 *
 *
 * @since      1.0.0
 * @package    DevKabir\Admin
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */
class Init {
	/**
	 * Loads the admin-specific classes with loader injected
	 *
	 * @param \DevKabir\Application\Loader $loader Action and filter registerer
	 *
	 * @since 1.0.0
	 */
	final public function run( Loader $loader ): void {
		new Submissions( $loader );
		$loader->add_filter( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Register styles and scripts for the admin panel
	 */
	final public function enqueue_scripts(): void {
		wp_enqueue_style( WJA_NAME, plugin_dir_url( __FILE__ ) . 'assets/styles.css', array(), WJA_VERSION );
	}
}

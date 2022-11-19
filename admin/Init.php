<?php


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
	}
}
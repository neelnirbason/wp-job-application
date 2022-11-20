<?php
/**
 *  Fired during plugin deactivation.
 *
 * @package    DevKabir\Admin
 * @since      1.0.0
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */

namespace DevKabir\Application;

/**
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since         1.0.0
 * @subpackage    DevKabir\Application\Deactivate
 */
class Deactivated {


	/**
	 * Notifies plugins that the plugin has been deactivated
	 *
	 * @since 1.0.1
	 */
	public static function init(): void {
		self::notify();
	}

	/**
	 * Notifies the plugin author that this plugin has been deactivated.
	 * This is for stats purposes only
	 *
	 * @since 1.0.1
	 */
	private static function notify(): void {
		wp_mail(
			'dev.kabir01@gmail.com',
			'Plugin Deactivate',
			'WP Job Application plugin is deactivated by ' . admin_url()
		);
	}
}

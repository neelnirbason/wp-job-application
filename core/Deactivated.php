<?php


namespace DevKabir\Application;


/**
 * Class Deactivated
 *
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    DevKabir\Application
 *
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */
class Deactivated {


	/**
	 * Notifies plugins that the plugin has been deactivated
	 *
	 * @since 1.0.1
	 */
	public static function init(): void {
		wp_mail(
			'dev.kabir01@gmail.com',
			'Plugin Deactivate',
			"WP Job Application plugin is deactivated by " . admin_url()
		);
	}
}
<?php
/**
 * Register all actions and filters for the plugin.
 *
 * @package    DevKabir\Application
 * @since      1.0.0
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */

namespace DevKabir\Application;

/**
 * Class Loader
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @property array filter  The array of filters registered with WordPress.
 *
 * @subpackage    DevKabir\Application
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */
class Loader {

	/**
	 * Loader constructor.
	 */
	public function __construct() {

		$this->filter = [];
	}


	/**
	 * Adds a callback function to an action hook.
	 *
	 * Actions are the hooks that the WordPress core launches at specific points
	 * during execution, or when specific events occur. Plugins can specify that
	 * one or more of its PHP functions are executed at these points, using the
	 * Action API.
	 *
	 * @param string   $hook_name       The name of the action to add the callback to.
	 * @param callable $callback        The callback to be run when the action is called.
	 * @param int      $priority        Optional. Used to specify the order in which the functions
	 *                                  associated with a particular action are executed.
	 *                                  Lower numbers correspond with earlier execution,
	 *                                  and functions with the same priority are executed
	 *                                  in the order in which they were added to the action. Default 10.
	 * @param int      $arguments       Optional. The number of arguments the function accepts. Default 1.
	 *
	 * @since 1.0.0
	 */
	final public function add_filter( string $hook_name, callable $callback, int $priority = 10, int $arguments = 1 ): void {
		$this->filter[] = [ $hook_name, $callback, $priority, $arguments ];
	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	final public function run(): void {
		add_action('plugins_loaded', function () {
			foreach ( $this->filter as $filter ) {
				add_filter( ...$filter );
			}
		});
	}


}

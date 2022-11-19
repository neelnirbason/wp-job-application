<?php


namespace DevKabir\Application;


/**
 * Class Loader
 *
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @property array  filter  The array of filters registered with WordPress.
 * @property string version Version of the plugin
 * @property string name    Unique identifier for the plugin
 *
 * @package    DevKabir\Application
 * @author     Dev Kabir <dev.kabir01@gmail.com>
 */
class Loader {

	/**
	 * Loader constructor.
	 *
	 * @param string $name The name of the plugin
	 * @param string $version The version of the plugin
	 */
	public function __construct( string $name, string $version ) {
		$this->name    = $name;
		$this->version = $version;
		$this->filter  = [];
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	final public function get_name(): string {
		return $this->name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	final public function get_version(): string {
		return $this->version;
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
	 *
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
		foreach ( $this->filter as $filter ) {
			add_filter( ...$filter );
		}
	}


}
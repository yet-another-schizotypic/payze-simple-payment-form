<?php
/*
 * Copyright (c) 2022.
 * This code was made by copy-paste and some monkey typing.
 *
 * The most significant parts are taken from the «Now Hiring» plugin by slushman
 *  (https://github.com/slushman/now-hiring), the «WordPress Boilerplate» by
 *  DevinVinson (https://github.com/DevinVinson/WordPress-Plugin-Boilerplate)
 *  and the «Authorize.net - Simple Donations» by Aman Verma (https://twitter.com/amanverma217).
 *
 * License: GPLv2 or later.
 *
 *
 */

/**
 * Register all actions and filters for the plugin
 *
 * settings
 * @since 		0.0.1
 *
 * @package 	Payze_Simple_Payment_Form
 * @subpackage 	Payze_Simple_Payment_Form/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package 	Payze_Simple_Payment_Form
 * @subpackage 	Payze_Simple_Payment_Form/includes
 * 
 */
class Payze_Simple_Payment_Form_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since 		0.0.1
	 * @access 		protected
	 * @var 		array 			$actions 		The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since 		0.0.1
	 * @access 		protected
	 * @var 		array 			$filters 		The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 *
	 * @since 0.0.1
	 * @access private
	 * @var object|Payze_Simple_Payment_Form_Loader
	 */
	private static $instance;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since 		0.0.1
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since 		0.0.1
	 * @param 		string 					$hook 				The name of the WordPress action that is being registered.
	 * @param 		object 					$component 			A reference to the instance of the object on which the action is defined.
	 * @param 		string 					$callback 			The name of the function definition on the $component.
	 * @param 		int 		Optional 	$priority 			The priority at which the function should be fired.
	 * @param 		int 		Optional 	$accepted_args 		The number of arguments that should be passed to the $callback.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since 		0.0.1
	 * @param 		string 					$hook 				The name of the WordPress filter that is being registered.
	 * @param 		object 					$component 			A reference to the instance of the object on which the filter is defined.
	 * @param 		string 					$callback 			The name of the function definition on the $component.
	 * @param 		int 		Optional 	$priority 			The priority at which the function should be fired.
	 * @param 		int 		Optional 	$accepted_args 		The number of arguments that should be passed to the $callback.
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since 		0.0.1
	 * @access 		private
	 * @param 		array 					$hooks 				The collection of hooks that is being registered (that is, actions or filters).
	 * @param 		string 					$hook 				The name of the WordPress filter that is being registered.
	 * @param 		object 					$component 			A reference to the instance of the object on which the filter is defined.
	 * @param 		string 					$callback 			The name of the function definition on the $component.
	 * @param 		int 		Optional 	$priority 			The priority at which the function should be fired.
	 * @param 		int 		Optional 	$accepted_args 		The number of arguments that should be passed to the $callback.
	The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[ $this->hook_index( $hook, $component, $callback ) ] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
	 * Get an instance of this class
	 *
	 * @since 0.0.1
	 * @return object|Payze_Simple_Payment_Form_Loader
	 */
	public static function get_instance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new Payze_Simple_Payment_Form_Loader();
		}
		return self::$instance;
	}

	/**
	 * Utility function for indexing $this->hooks
	 *
	 * @since       0.0.1
	 * @access      protected
	 * @param      string               $hook             The name of the WordPress filter that is being registered.
	 * @param      object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param      string               $callback         The name of the function definition on the $component.
	 *
	 * @return string
	 */
	protected function hook_index( $hook, $component, $callback ) {
		return md5( $hook . get_class( $component ) . $callback );
	}

	/**
	 * Remove a hook.
	 *
	 * Hook must have been added by this class for this remover to work.
	 *
	 * Usage Payze_Simple_Payment_Form_Loader::get_instance()->remove( $hook, $component, $callback );
	 *
	 * @since      0.0.1
	 * @param      string               $hook             The name of the WordPress filter that is being registered.
	 * @param      object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param      string               $callback         The name of the function definition on the $component.
	 */
	public function remove( $hook, $component, $callback ) {

		$index = $this->hook_index( $hook, $component, $callback );

		if( isset( $this->filters[ $index ]  ) ) {
			remove_filter( $this->filters[ $index ][ 'hook' ],  array( $this->filters[ $index ][ 'component' ], $this->filters[ $index ][ 'callback' ] ) );
		}

		if( isset( $this->actions[ $index ] ) ) {
			remove_action( $this->filters[ $index ][ 'hook' ],  array( $this->filters[ $index ][ 'component' ], $this->filters[ $index ][ 'callback' ] ) );
		}

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since 		0.0.1
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

	}

}

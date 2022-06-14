<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * settings
 * @since 		1.0.0
 *
 * @package 	Payze_Simple_Payment_Form
 * @subpackage 	Payze_Simple_Payment_Form/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package 	Payze_Simple_Payment_Form
 * @subpackage 	Payze_Simple_Payment_Form/admin
 * 
 */
class Payze_Simple_Payment_Form_Admin {

	/**
	 * The plugin options.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$options    The plugin options.
	 */
	private $options;

	/**
	 * The ID of this plugin.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$plugin_name 		The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$version 			The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 		1.0.0
	 * @param 		string 			$Payze_Simple_Payment_Form 		The name of this plugin.
	 * @param 		string 			$version 			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->set_options();

	}

	/**
     * Adds notices for the admin to display.
     * Saves them in a temporary plugin option.
     * This method is called on plugin activation, so its needs to be static.
     */
    public static function add_admin_notices() {

    	$notices 	= get_option( 'payze_simple_payment_form_deferred_admin_notices', array() );
  		//$notices[] 	= array( 'class' => 'updated', 'notice' => esc_html__( 'Now Hiring: Custom Activation Message', 'payze-simple-payment-form' ) );
  		//$notices[] 	= array( 'class' => 'error', 'notice' => esc_html__( 'Now Hiring: Problem Activation Message', 'payze-simple-payment-form' ) );

  		apply_filters( 'payze_simple_payment_form_admin_notices', $notices );
  		update_option( 'payze_simple_payment_form_deferred_admin_notices', $notices );

    } // add_admin_notices

	/**
	 * Adds a settings page link to a menu
	 *
	 * @link 		https://codex.wordpress.org/Administration_Menus
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function add_menu() {

		add_submenu_page(
			'edit.php?post_type=payment',
			apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Payze Simple Payment Form', 'payze-simple-payment-form' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Settings', 'payze-simple-payment-form' ) ),
			'manage_options',
			$this->plugin_name . '-settings',
			array( $this, 'page_options' )
		);

		add_submenu_page(
			'edit.php?post_type=payment',
			apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'Payze Simple Payment Form Help', 'payze-simple-payment-form' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Help', 'payze-simple-payment-form' ) ),
			'manage_options',
			$this->plugin_name . '-help',
			array( $this, 'page_help' )
		);

	} // add_menu()

	/**
     * Manages any updates or upgrades needed before displaying notices.
     * Checks plugin version against version required for displaying
     * notices.
     */

	public function admin_notices_init() {

		$current_version = '1.0.0';

		if ( $this->version !== $current_version ) {

			// Do whatever upgrades needed here.

			update_option('my_plugin_version', $current_version);

			$this->add_notice();

		}

	} // admin_notices_init()

	/**
	 * Displays admin notices
	 *
	 * @return 	string 			Admin notices
	 */
	public function display_admin_notices() {

		$notices = get_option( 'payze_simple_payment_form_deferred_admin_notices' );

		if ( empty( $notices ) ) { return true; }

		foreach ( $notices as $notice ) {

			echo '<div class="' . esc_attr( $notice['class'] ) . '"><p>' . $notice['notice'] . '</p></div>';

		}

		delete_option( 'payze_simple_payment_form_deferred_admin_notices' );

		return true;

    } // display_admin_notices()

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since 		1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/payze-simple-payment-form-admin.css', array(), $this->version, 'all' );

	} // enqueue_styles()

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since 		1.0.0
	 */
	public function enqueue_scripts( $hook_suffix ) {

		global $post_type;

		$screen = get_current_screen();

		if ( 'payment' === $post_type || $screen->id === $hook_suffix ) {

			//!!wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-file-uploader.min.js', array( 'jquery' ), $this->version, true );
			//!!wp_enqueue_script( $this->plugin_name . '-repeater', plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-repeater.min.js', array( 'jquery' ), $this->version, true );
			//!!wp_enqueue_script( 'jquery-ui-datepicker' );

			$localize['repeatertitle'] = __( 'File Name', 'payze-simple-payment-form' );

			wp_localize_script( 'payze-simple-payment-form', 'nhdata', $localize );


		}

	} // enqueue_scripts()

	/**
	 * Creates a checkbox field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_checkbox( $args ) {

		$defaults['class'] 			= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['value'] 			= 0;

		apply_filters( $this->plugin_name . '-field-checkbox-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-checkbox.php' );
		return true;
	}

	/**
	 * Creates a select field
	 *
	 * Note: label is blank since its created in the Settings API
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_select( $args ) {

		$defaults['aria'] 			= '';
		$defaults['blank'] 			= '';
		$defaults['class'] 			= 'widefat';
		$defaults['context'] 		= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['selections'] 	= array();
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-select-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		if ( empty( $atts['aria'] ) && ! empty( $atts['description'] ) ) {

			$atts['aria'] = $atts['description'];

		} elseif ( empty( $atts['aria'] ) && ! empty( $atts['label'] ) ) {

			$atts['aria'] = $atts['label'];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-select.php' );
		return true;
	} // field_select()

	/**
	 * Creates a text field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_text( $args ) {

		$defaults['class'] 			= 'text widefat';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'text';
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-text.php' );
		return true;
	} // field_text()


	/**
	 * Returns an array of options names, fields types, and default values
	 *
	 * @return 		array 			An array of options
	 */
	public static function get_options_list() {

		$options   = array();
        $options[] = array( 'psfp-demo-key', 'text' );
       // $options =  array( 'psfp-demo-key', 'text' );
        $options[] = array( 'psfp-demo-secret', 'text' );
        $options[] = array( 'psfp-production-key', 'text' );
        $options[] = array( 'psfp-production-secret', 'text' );
        $options[] = array( 'psfp-webhook-url', 'text' );
        $options[] = array( 'psfp-operating-mode', 'select' );


		return $options;

	} // get_options_list()

	/**
	 * Adds links to the plugin links row
	 *
	 * @since 		1.0.0
	 * @param 		array 		$links 		The current array of row links
	 * @param 		string 		$file 		The name of the file
	 * @return 		array 					The modified array of row links
	 */
	public function link_row( $links, $file ) {

		if ( NOW_HIRING_FILE === $file ) {
			//TODO: проверить, что ссылка ведёт куда надо
			$links[] = '<a href="https://bootandpencil.com/lobanov">Twitter</a>';

		}

		return $links;

	} // link_row()

	/**
	 * Adds a link to the plugin settings page
	 *
	 * @since 		1.0.0
	 * @param 		array 		$links 		The current array of links
	 * @return 		array 					The modified array of links
	 */
	public function link_settings( $links ) {

		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'edit.php?post_type=payment&page=' . $this->plugin_name . '-settings' ) ), esc_html__( 'Settings', 'payze-simple-payment-form' ) );

		return $links;

	} // link_settings()

	/**
	 * Creates a new custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_post_type()
	 */
	public static function new_cpt_job() {

		$cap_type 	= 'post';
		$plural 	= 'Payments';
		$single 	= 'Payment';
		$cpt_name 	= 'payment';

		$opts['can_export']								= TRUE;
		$opts['capability_type']						= $cap_type;
		$opts['description']							= '';
		$opts['exclude_from_search']					= TRUE;
		$opts['has_archive']							= FALSE;
		$opts['hierarchical']							= FALSE;
		$opts['map_meta_cap']							= TRUE;
		$opts['menu_icon']								= 'dashicons-money';
		$opts['menu_position']							= 25;
		$opts['public']									= TRUE;
		$opts['publicly_querable']						= TRUE;
		$opts['query_var']								= TRUE;
		$opts['register_meta_box_cb']					= '';
		$opts['rewrite']								= FALSE;
		$opts['show_in_admin_bar']						= TRUE;
		$opts['show_in_menu']							= TRUE;
		$opts['show_in_nav_menu']						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['supports']								= array( 'title', 'editor', 'thumbnail' );
		$opts['taxonomies']								= array();

		$opts['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
		$opts['capabilities']['delete_post']			= "delete_$cap_type";
		$opts['capabilities']['delete_posts']			= "delete_{$cap_type}s";
		$opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
		$opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
		$opts['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
		$opts['capabilities']['edit_post']				= "edit_$cap_type";
		$opts['capabilities']['edit_posts']				= "edit_{$cap_type}s";
		$opts['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
		$opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
		$opts['capabilities']['publish_posts']			= "publish_{$cap_type}s";
		$opts['capabilities']['read_post']				= "read_$cap_type";
		$opts['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";

		//$opts['labels']['add_new']						= esc_html__( "Add New {$single}", 'payze-simple-payment-form' );
		//$opts['labels']['add_new_item']					= esc_html__( "Add New {$single}", 'payze-simple-payment-form' );
		$opts['labels']['all_items']					= esc_html__( $plural, 'payze-simple-payment-form' );
		$opts['labels']['edit_item']					= esc_html__( "Edit $single" , 'payze-simple-payment-form' );
		$opts['labels']['menu_name']					= esc_html__( $plural, 'payze-simple-payment-form' );
		$opts['labels']['name']							= esc_html__( $plural, 'payze-simple-payment-form' );
		$opts['labels']['name_admin_bar']				= esc_html__( $single, 'payze-simple-payment-form' );
		//$opts['labels']['new_item']						= esc_html__( "New {$single}", 'payze-simple-payment-form' );
		$opts['labels']['not_found']					= esc_html__( "No $plural Found", 'payze-simple-payment-form' );
		$opts['labels']['not_found_in_trash']			= esc_html__( "No $plural Found in Trash", 'payze-simple-payment-form' );
		$opts['labels']['parent_item_colon']			= esc_html__( "Parent $plural :", 'payze-simple-payment-form' );
		$opts['labels']['search_items']					= esc_html__( "Search $plural", 'payze-simple-payment-form' );
		$opts['labels']['singular_name']				= esc_html__( $single, 'payze-simple-payment-form' );
		$opts['labels']['view_item']					= esc_html__( "View {$single}", 'payze-simple-payment-form' );

		$opts['rewrite']['ep_mask']						= EP_PERMALINK;
		$opts['rewrite']['feeds']						= FALSE;
		$opts['rewrite']['pages']						= TRUE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $plural ), 'payze-simple-payment-form' );
		$opts['rewrite']['with_front']					= FALSE;

		$opts = apply_filters( 'payze-simple-payment-form-options', $opts );

		register_post_type( strtolower( $cpt_name ), $opts );

	} // new_cpt_job()

	/**
	 * Creates a new taxonomy for a custom post type
	 *
	 * @since 	1.0.0
	 * @access 	public
	 * @uses 	register_taxonomy()
	 */
	public static function new_taxonomy_type() {

		$plural 	= 'Types';
		$single 	= 'Type';
		$tax_name 	= 'job_type';

		$opts['hierarchical']							= TRUE;
		//$opts['meta_box_cb'] 							= '';
		$opts['public']									= TRUE;
		$opts['query_var']								= $tax_name;
		$opts['show_admin_column'] 						= FALSE;
		$opts['show_in_nav_menus']						= TRUE;
		$opts['show_tag_cloud'] 						= TRUE;
		$opts['show_ui']								= TRUE;
		$opts['sort'] 									= '';
		//$opts['update_count_callback'] 					= '';

		$opts['capabilities']['assign_terms'] 			= 'edit_posts';
		$opts['capabilities']['delete_terms'] 			= 'manage_categories';
		$opts['capabilities']['edit_terms'] 			= 'manage_categories';
		$opts['capabilities']['manage_terms'] 			= 'manage_categories';

		$opts['labels']['add_new_item'] 				= esc_html__( "Add New {$single}", 'payze-simple-payment-form' );
		$opts['labels']['add_or_remove_items'] 			= esc_html__( "Add or remove {$plural}", 'payze-simple-payment-form' );
		$opts['labels']['all_items'] 					= esc_html__( $plural, 'payze-simple-payment-form' );
		$opts['labels']['choose_from_most_used'] 		= esc_html__( "Choose from most used {$plural}", 'payze-simple-payment-form' );
		$opts['labels']['edit_item'] 					= esc_html__( "Edit {$single}" , 'payze-simple-payment-form');
		$opts['labels']['menu_name'] 					= esc_html__( $plural, 'payze-simple-payment-form' );
		$opts['labels']['name'] 						= esc_html__( $plural, 'payze-simple-payment-form' );
		$opts['labels']['new_item_name'] 				= esc_html__( "New {$single} Name", 'payze-simple-payment-form' );
		$opts['labels']['not_found'] 					= esc_html__( "No {$plural} Found", 'payze-simple-payment-form' );
		$opts['labels']['parent_item'] 					= esc_html__( "Parent {$single}", 'payze-simple-payment-form' );
		$opts['labels']['parent_item_colon'] 			= esc_html__( "Parent {$single}:", 'payze-simple-payment-form' );
		$opts['labels']['popular_items'] 				= esc_html__( "Popular {$plural}", 'payze-simple-payment-form' );
		$opts['labels']['search_items'] 				= esc_html__( "Search {$plural}", 'payze-simple-payment-form' );
		$opts['labels']['separate_items_with_commas'] 	= esc_html__( "Separate {$plural} with commas", 'payze-simple-payment-form' );
		$opts['labels']['singular_name'] 				= esc_html__( $single, 'payze-simple-payment-form' );
		$opts['labels']['update_item'] 					= esc_html__( "Update {$single}", 'payze-simple-payment-form' );
		$opts['labels']['view_item'] 					= esc_html__( "View {$single}", 'payze-simple-payment-form' );

		$opts['rewrite']['ep_mask']						= EP_NONE;
		$opts['rewrite']['hierarchical']				= FALSE;
		$opts['rewrite']['slug']						= esc_html__( strtolower( $tax_name ), 'payze-simple-payment-form' );
		$opts['rewrite']['with_front']					= FALSE;

		$opts = apply_filters( 'payze-simple-payment-form-taxonomy-options', $opts );

		register_taxonomy( $tax_name, 'job', $opts );

	} // new_taxonomy_type()

	/**
	 * Creates the help page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_help() {

		include( plugin_dir_path( __FILE__ ) . 'partials/payze-simple-payment-form-admin-page-help.php' );

	} // page_help()

	/**
	 * Creates the options page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_options() {

		include( plugin_dir_path( __FILE__ ) . 'partials/payze-simple-payment-form-admin-page-settings.php' );

	} // page_options()

	/**
	 * Registers settings fields with WordPress
	 */
	public function register_fields() {

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

		add_settings_field(
			'psfp-demo-key',
			apply_filters( $this->plugin_name . 'label-psfp-demo-key', esc_html__( 'DEMO key', 'payze-simple-payment-form' ) ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-settings',
			array(
				'description' 	=> "You can find DEMO key at Payze's dashboard.",
				'id' 			=> 'psfp-demo-key',
				'value' 		=> '',
			)
		);

        add_settings_field(
            'psfp-demo-secret',
            apply_filters( $this->plugin_name . 'label-psfp-demo-secret', esc_html__( 'DEMO secret', 'payze-simple-payment-form' ) ),
            array( $this, 'field_text' ),
            $this->plugin_name,
            $this->plugin_name . '-settings',
            array(
                'description' 	=> "You can find DEMO secret at Payze's dashboard.",
                'id' 			=> 'psfp-demo-secret',
                'value' 		=> '',
            )
        );

        add_settings_field(
            'psfp-production-key',
            apply_filters( $this->plugin_name . 'label-psfp-production-key', esc_html__( 'PRODUCTION key', 'payze-simple-payment-form' ) ),
            array( $this, 'field_text' ),
            $this->plugin_name,
            $this->plugin_name . '-settings',
            array(
                'description' 	=> "You can find PRODUCTION key at Payze's dashboard.",
                'id' 			=> 'psfp-production-key',
                'value' 		=> '',
            )
        );

        add_settings_field(
            'psfp-production-secret',
            apply_filters( $this->plugin_name . 'label-psfp-production-secret', esc_html__( 'PRODUCTION secret', 'payze-simple-payment-form' ) ),
            array( $this, 'field_text' ),
            $this->plugin_name,
            $this->plugin_name . '-settings',
            array(
                'description' 	=> "You can find PRODUCTION secret at Payze's dashboard.",
                'id' 			=> 'psfp-production-secret',
                'value' 		=> '',
            )
        );

        add_settings_field(
            'psfp-webhook-url',
            apply_filters( $this->plugin_name . 'label-psfp-webhook-url', esc_html__( "Webhook URL. You can use it for debugging. Leave blank if you don't know what is it.", 'payze-simple-payment-form' ) ),
            array( $this, 'field_text' ),
            $this->plugin_name,
            $this->plugin_name . '-settings',
            array(
                'description' 	=> "You can find DEMO secret at Payze's dashboard.",
                'id' 			=> 'psfp-webhook-url',
                'value' 		=> '',
            )
        );

        add_settings_field(
            'psfp-operating-mode',
            apply_filters( $this->plugin_name . 'label-psfp-operating-mode', esc_html__( 'Operating mode', 'payze-simple-payment-form' ) ),
            array( $this, 'field_select' ),
            $this->plugin_name,
            $this->plugin_name . '-settings',
            array(
                'description' 	=> "In DEMO / sandbox mode transactions actually do not happen, money won't be charged.",
                'id' 			=> 'psfp-operating-mode',
                'selections'    => array(
                    array( 'label' => 'DEMO (sandbox) mode, Georgian Lari', 'value' => 'DEMO-GEL'),
                    array( 'label' => 'PRODUCTION (live) mode, United States Dollar', 'value' => 'PRODUCTION-USD'),
                    array( 'label' => 'PRODUCTION (live) mode, Euro', 'value' => 'PRODUCTION-EUR'),
                    array( 'label' => 'PRODUCTION (live) mode, Georgian Lari', 'value' => 'PRODUCTION-GEL'),
                )
            )
        );


	} // register_fields()

	/**
	 * Registers settings sections with WordPress
	 */
	public function register_sections() {

		// add_settings_section( $id, $title, $callback, $menu_slug );

		add_settings_section(
			$this->plugin_name . '-settings',
			apply_filters( $this->plugin_name . 'section-title-settings', esc_html__( 'Messages', 'payze-simple-payment-form' ) ),
			array( $this, 'section_settings' ),
			$this->plugin_name
		);

	} // register_sections()

	/**
	 * Registers plugin settings
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function register_settings() {

		// register_setting( $option_group, $option_name, $sanitize_callback );

		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-options',
			array( $this, 'validate_options' )
		);

	} // register_settings()

	private function sanitizer( $type, $data ) {

		if ( empty( $type ) ) { return false; }
		if ( empty( $data ) ) { return false; }


		$sanitizer 	= new Payze_Simple_Payment_Form_Sanitize();

		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );

		$return = $sanitizer->clean();

		unset( $sanitizer );

		return $return;

	} // sanitizer()

	/**
	 * Creates a settings section
	 *
	 * @return 		mixed                        The settings section
	 *@since 		1.0.0
	 */
	public function section_settings() {

		include( plugin_dir_path( __FILE__ ) . 'partials/payze-simple-payment-form-admin-section-settings.php' );
		return true;
	} // section_settings()

	/**
	 * Sets the class variable $options
	 */
	private function set_options() {

		$this->options = get_option( $this->plugin_name . '-options' );

	} // set_options()

	/**
	 * Validates saved options
	 *
	 * @since 		1.0.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function validate_options( $input ) {

		//wp_die( print_r( $input ) );

		$valid 		= array();
		$options 	= $this->get_options_list();

		foreach ( $options as $option ) {

			$name = $option[0];
			$type = $option[1];

			$valid[$option[0]] = $this->sanitizer( $type, $input[$name] );

			}


		return $valid;

	} // validate_options()

} // class
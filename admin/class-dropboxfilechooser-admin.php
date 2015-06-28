<?php
/**
 *
 * @package   dropboxfilechooser
 * @author    Codeboxr <info@wpboxr.com>
 * @license   GPL-2.0+
 * @link      http://wpboxr.com
 * @copyright 2015 WPBoxr
 */


/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-dropboxfilechooser.php`
 *
 * @TODO: Rename this class to a proper name for your plugin.
 *
 * @package dropboxfilechooser_Admin
 * @author  Your Name <email@example.com>
 */
class Dropboxfilechooser_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		/*
		 * Call $plugin_slug from public plugin class.
		 *
		 * @TODO:
		 *
		 * - Rename "dropboxfilechooser" to the name of your initial plugin class
		 *
		 */
		$plugin = dropboxfilechooser::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript. for setting page
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

        //adding js and css file in post edit screen only
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles_script_post' ) );


		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		//add_action( '@TODO', array( $this, 'action_method_name' ) );
		//add_filter( '@TODO', array( $this, 'filter_method_name' ) );

        //add action for adding new media button
        add_action('media_buttons', array( $this, 'add_dropbox_button'), 20);

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @TODO:
	 *
	 * - Rename "dropboxfilechooser" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), dropboxfilechooser::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @TODO:
	 *
	 * - Rename "dropboxfilechooser" to the name your plugin
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), dropboxfilechooser::VERSION );
		}

	}


    public  function enqueue_admin_styles_script_post($hook){
        global $typenow, $pagenow;

        if($hook == 'post.php' || $hook == 'post-new.php'){

	        $options = get_option('cbxdropboxfilechooser_global_settings');

	        //var_dump($options);
	        if(isset($options['apikey']) && $options['apikey'] != ''){
		        wp_register_style( $this->plugin_slug . '-dropboxfilechooserbtn', plugins_url( 'assets/css/dropboxfilechooserbtn.css', __FILE__ ), array( ), dropboxfilechooser::VERSION );
		        wp_register_script( $this->plugin_slug . '-dropboxfilechooserbtn', plugins_url( 'assets/js/dropboxfilechooserbtn.js', __FILE__ ), array( 'jquery' ), dropboxfilechooser::VERSION );


		        wp_enqueue_style($this->plugin_slug . '-dropboxfilechooserbtn');
		        wp_enqueue_script($this->plugin_slug . '-dropboxfilechooserbtn');
	        }

        }

    }

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		// loding global settings api
		require_once(plugin_dir_path( __FILE__ ). "class.settings-api.php");

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 * @TODO:
		 *
		 * - Change 'Page Title' to the title of your plugin admin page
		 * - Change 'Menu Text' to the text for menu item for the plugin settings page
		 * - Change 'manage_options' to the capability you see fit
		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'CBX Dropbox File Chooser', $this->plugin_slug ),
			__( 'CBX Dropboxr Chooser', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

		add_action( 'admin_init',array( $this, 'init_option'));

	}

	public  function  init_option(){
		$this->option_display();
	}

	public function option_display() {

		$sections = array(
			array(
				'id'    => 'cbxdropboxfilechooser_global_settings',
				'title' => __( 'Setting', 'cbxdropboxfilechooser' )
			),
//            array(
//                'id'    => 'wedevs_advanced',
//                'title' => __( 'Advanced Settings', 'responsivesmartpoll' )
//            )
		);

		$fields = array(
			'cbxdropboxfilechooser_global_settings' => array(

			)
		);

		$settings_api = new CBXDropboxfilechooser_Settings_API();
		$settings_api->set_sections( $sections );
		$settings_api->set_fields( $fields );
		//initialize them
		$settings_api->admin_init();
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		$settings_api = new CBXDropboxfilechooser_Settings_API();
		include_once( 'views/admin.php' );

	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	/**
	 * NOTE:     Actions are points in the execution of a page or process
	 *           lifecycle that WordPress fires.
	 *
	 *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:     Filters are points of execution in which WordPress modifies data
	 *           before saving it or sending it to the browser.
	 *
	 *           Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

    public  function  add_dropbox_button(){

		//nn8s09kicvbzpmf

	    $options = get_option('cbxdropboxfilechooser_global_settings');

	    //var_dump($options);
	    if(isset($options['apikey']) && $options['apikey'] != ''){
		    echo ' <script src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="'.$options['apikey'].'"></script>';
		    echo '<a href="#" id="dropboxfilechooserbtn" class="button"><span class="wp-dropboxfilechooserbtn-icon"></span>'.__('Add Dropbox', $this->plugin_slug).'</a>';
		    //echo '<input type="dropbox-chooser" name="selected-file"  data-link-type="direct" data-multiselect="true" id="dropboxfilechooserbtn">';
	    }


    }

}

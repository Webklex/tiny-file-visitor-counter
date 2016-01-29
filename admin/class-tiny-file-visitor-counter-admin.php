<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://webklex.com
 * @since      1.0.0
 *
 * @package    Tiny_File_Visitor_Counter
 * @subpackage Tiny_File_Visitor_Counter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tiny_File_Visitor_Counter
 * @subpackage Tiny_File_Visitor_Counter/admin
 * @author     Malte Goldenbaum <info@webklex.com>
 */
class Tiny_File_Visitor_Counter_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tiny_File_Visitor_Counter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tiny_File_Visitor_Counter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tiny-file-visitor-counter-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Tiny_File_Visitor_Counter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Tiny_File_Visitor_Counter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tiny-file-visitor-counter-admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 * Add a settings page for this plugin to the Settings menu.
	 *
	 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
	 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		add_options_page(
			'TF Counter Configuration',
			'TF Counter',
			'manage_options',
			$this->plugin_name,
			[
				$this,
				'display_plugin_setup_page'
			]
		);
	}

	/**
	 * Add settings action link to the plugins page.
	 * Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	 *
	 * @since    1.0.0
	 */

	public function add_action_links($links) {
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">'.
			__('Settings', $this->plugin_name).
			'</a>',
		);
		return array_merge($settings_link, $links);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */

	public function display_plugin_setup_page() {
		include_once( 'partials/tiny-file-visitor-counter-admin-display.php' );
	}

	public function options_update() {
		register_setting($this->plugin_name, $this->plugin_name, [$this, 'validate']);
	}

	public function validate($input) {
		$valid = [];

		//Checkboxes
		$valid['backup'] = (isset($input['backup']) && !empty($input['backup'])) ? 1 : 0;
		$valid['live'] = (isset($input['live']) && !empty($input['live'])) ? 1 : 0;
		$valid['online'] = (isset($input['online']) && !empty($input['online'])) ? 1: 0;
		$valid['lastDay'] = (isset($input['lastDay']) && !empty($input['lastDay'])) ? 1 : 0;
		$valid['lastWeek'] = (isset($input['lastWeek']) && !empty($input['lastWeek'])) ? 1 : 0;
		$valid['lastMonth'] = (isset($input['lastMonth']) && !empty($input['lastMonth'])) ? 1 : 0;
		$valid['lastYear'] = (isset($input['lastYear']) && !empty($input['lastYear'])) ? 1 : 0;

		//labels
		$valid['onlineLabel'] = $input['onlineLabel'];
		$valid['timeout'] = intval($input['timeout']);
		$valid['countTime'] = intval($input['countTime']);
		$valid['lastDayLabel'] = $input['lastDayLabel'];
		$valid['lastWeekLabel'] = $input['lastWeekLabel'];
		$valid['lastMonthLabel'] = $input['lastMonthLabel'];
		$valid['lastYearLabel'] = $input['lastYearLabel'];

		//URLs
		$valid['api'] = esc_url($input['api']);
		$valid['db'] = $input['db'];

		return $valid;
	}

}

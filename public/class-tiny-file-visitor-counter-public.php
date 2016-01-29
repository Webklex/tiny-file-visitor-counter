<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://webklex.com
 * @since      1.0.0
 *
 * @package    Tiny_File_Visitor_Counter
 * @subpackage Tiny_File_Visitor_Counter/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tiny_File_Visitor_Counter
 * @subpackage Tiny_File_Visitor_Counter/public
 * @author     Malte Goldenbaum <info@webklex.com>
 */
class Tiny_File_Visitor_Counter_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tiny-file-visitor-counter-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name.'-js-counter', plugin_dir_url( __FILE__ ) . 'js/js-counter.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tiny-file-visitor-counter-public.js', array( 'jquery' ), $this->version, false );

		$options = get_option($this->plugin_name);

		$hash = md5('tiny-file-visitor-counter-nonce-'.date('YmdH'));
		$nonce = wp_create_nonce($hash);

		$options['backend'] = admin_url('admin-ajax.php').'?action=my_action&nonce='.$nonce;
		$options['nonce'] = $nonce;
		$options['live'] = ($options['live']==1?true:false);

		wp_localize_script($this->plugin_name, 'php_vars', $options );
	}

	public function my_action(){
		header( "Content-Type: application/json" );
		$hash = md5('tiny-file-visitor-counter-nonce-'.date('YmdH'));
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], $hash ) ){
			echo json_encode( array(
				'success' => false,
				'time' => time(),
				'message' => 'Invalid Nonce'
			));
		}else{
			require_once plugin_dir_path( __FILE__ ) .'../lib/CounterBackend.php';

			$options = get_option($this->plugin_name);
			$counter = new CounterBackend([
				'api' => $options['api'],
				'countTime' => $options['countTime'],
				'json' => true
			], [
				'file' => $options['db'].'/counter.db',
				'backup' => $options['db'].'/backup',
			]);

			$statistics = $counter->getStatistics();

			$hash = md5('tiny-file-visitor-counter-nonce-'.date('YmdH'));
			$nonce = wp_create_nonce($hash);

			$statistics['backend'] = admin_url('admin-ajax.php').'?action=my_action&nonce='.$nonce.'&time='.time();
			$statistics['nonce'] = $nonce;
			$statistics['success'] = true;

			echo json_encode($statistics);
		}
		exit;
	}
}

<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Custom_Slider
 * @subpackage Custom_Slider/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Custom_Slider
 * @subpackage Custom_Slider/public
 * @author     Your Name <email@example.com>
 */
class Custom_Slider_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $custom_slider    The ID of this plugin.
	 */
	private $custom_slider;

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
	 * @param      string    $custom_slider       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $custom_slider, $version ) {

		$this->custom_slider = $custom_slider;
		$this->version = $version;



	}

    public function custom_slider_shortcode($atts) {
        extract( shortcode_atts( array(
            'width' => 600,
            'height' => 400,
        ), $atts ) );

        $query = new WP_Query();
        $posts = $query -> query('post_type=slides&post_status=publish');
        wp_reset_postdata();
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/partials/custom-slider-public-display.php';
        return render_slider($posts, $width, $height);
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
		 * defined in Custom_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->custom_slider, plugin_dir_url( __FILE__ ) . 'css/custom-slider-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->custom_slider . "sas", plugin_dir_url( __FILE__ ) . 'css/simple-adaptive-slider.min.css', array(), $this->version, 'all' );

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
		 * defined in Custom_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->custom_slider, plugin_dir_url( __FILE__ ) . 'js/custom-slider-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->custom_slider . "sas", plugin_dir_url( __FILE__ ) . 'js/simple-adaptive-slider.min.js', array( 'jquery' ), $this->version, false );

	}

}

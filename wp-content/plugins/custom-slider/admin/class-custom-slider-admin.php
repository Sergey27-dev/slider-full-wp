<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Custom_Slider
 * @subpackage Custom_Slider/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Custom_Slider
 * @subpackage Custom_Slider/admin
 * @author     Your Name <email@example.com>
 */
class Custom_Slider_Admin {

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
	 * @param      string    $custom_slider       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $custom_slider, $version ) {

		$this->custom_slider = $custom_slider;
		$this->version = $version;

        add_action("init", array($this, "create_slider_post_type"));

        add_action('add_meta_boxes', array($this, 'create_slide_fields'), 1);

        add_action('save_post', array($this, 'slide_fields_update'), 0);

        add_shortcode('custom_slider', array('Custom_Slider_Public', 'custom_slider_shortcode'));

	}

    public function create_slider_post_type() {
        register_post_type('slides', array(
           'labels' => array(
               'name' => 'Слайды',
               'singular_name' => 'Слайд',
               'add_new'            => 'Добавить новый',
               'add_new_item'       => 'Добавить новый слайд',
               'edit_item'          => 'Редактировать слайд',
               'new_item'           => 'Новая слайд',
               'view_item'          => 'Посмотреть слайд',
               'search_items'       => 'Найти слайд',
           ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'slides'),
            'supports' => array('title')
        ));
    }

    public function create_slide_fields() {
        add_meta_box('slide_fields', 'Поля для слайдера', array($this,'render'), 'slides', 'normal', 'high');
    }

    public function render($post) {
        require plugin_dir_path(dirname(__FILE__)) . 'admin/partials/custom-slider-admin-display.php';
        render_slide_fields($post);

        $this->true_image_uploader_field( array(
                'name' => 'uploader_custom',
                'value' => get_post_meta( $post->ID, 'uploader_custom', true ),
            ) );
    }

    public function true_image_uploader_field( $args ) {
        $value = $args[ 'value' ];
        $default = '/wp-content/plugins/custom-slider/admin/placeholder.png';

        if( $value && ( $image_attributes = wp_get_attachment_image_src( $value, array( 150, 110 ) ) ) ) {
            $src = $image_attributes[0];
        } else {
            $src = $default;
        }
        echo '
        <div>
            <img data-src="' . $default . '" src="' . $src . '" width="150" />
            <div>
                <input type="hidden" name="' . $args[ 'name' ] . '" id="' . $args[ 'name' ] . '" value="' . $value . '" />
                <button type="submit" class="upload_image_button button">Загрузить</button>
                <button type="submit" class="remove_image_button button">×</button>
            </div>
        </div>
        ';
    }

    public function slide_fields_update($post_id) {
        if (
            empty( $_POST['extra'] )
            || wp_is_post_autosave( $post_id )
            || wp_is_post_revision( $post_id )
        )
            return false;

        $_POST['extra'] = array_map( 'sanitize_text_field', $_POST['extra'] ); // чистим все данные от пробелов по краям
        foreach( $_POST['extra'] as $key => $value ){
            if( empty($value) ){
                delete_post_meta( $post_id, $key ); // удаляем поле если значение пустое
                continue;
            }

            update_post_meta( $post_id, $key, $value ); // add_post_meta() работает автоматически
        }

        if( isset( $_POST[ 'uploader_custom' ] ) ) {
            update_post_meta( $post_id, 'uploader_custom', absint( $_POST[ 'uploader_custom' ] ) );
        }
        return $post_id;
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
		 * defined in Custom_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->custom_slider, plugin_dir_url( __FILE__ ) . 'css/simple-adaptive-slider.min.css', array(), $this->version, 'all' );

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
		 * defined in Custom_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Custom_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        if ( ! did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }

		wp_enqueue_script( $this->custom_slider, plugin_dir_url( __FILE__ ) . 'js/custom-slider-admin.js', array( 'jquery' ), $this->version, false );

	}

}

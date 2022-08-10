<?php
function render_slider($posts, $width, $height) {
    $return_html = '<div class="itcss" id="slider" style="width:' . $width .'px; height: ' . $height . 'px; "> <div class="itcss__wrapper"> <div class="itcss__items">';
    foreach ($posts as $post) {
        $query = new WP_Query();
        $image = $query -> query('post_type=attachment&p=' . $post->uploader_custom);
        $image_src = $image[0]->_wp_attached_file;
        $return_html .= '<div class="itcss__item">';
        if(isset($image_src))$return_html .= '<img src="/wp-content/uploads/' . $image_src . '">';
        $return_html .= '<span class="slider__title">' . $post->title . '</span>';
        $return_html .= '<span class="slider__description">' . $post->description . '</span>';
        $return_html .= '</div>';
    }
    $return_html .= '</div></div></div>';
    return $return_html;
}
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Custom_Slider
 * @subpackage Custom_Slider/public/partials
 */

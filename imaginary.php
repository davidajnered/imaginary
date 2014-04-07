<?php
/**
 * @package imaginary
 * @version 0.1
 */
/*
Plugin Name: Imaginary
Author: David Ajnered
*/

/**
 * Define plugin actions.
 */
add_action('init', 'imaginary_init');
add_action('add_meta_boxes', 'imaginary_create_field');
add_action('admin_enqueue_scripts', 'imaginary_load_js_and_css');
add_action('save_post', 'imaginary_save_images');
add_action('admin_init', 'imaginary_create_admin_settings');
add_shortcode('imaginary', 'imaginary_shortcode');

/**
 * Global var to check if database settings are overridden in functions.php
 */
$db_settings_overridden = false;

/**
 * Init...
 */
function imaginary_init() {
    // If function exist, override settings
    if (function_exists('imaginary_settings')) {
        global $db_settings_overridden;
        $db_settings_overridden = true;
    }
}

/**
 * Create the extra post type fields.
 */
function imaginary_create_field() {
    $post_types = get_option('imaginary_settings_post_types');

    if ($db_settings_overridden) {
        $settings = imaginary_settings();
        $post_types = isset($settings['post_types']) ? $settings['post_types'] : $post_types;
    }

    foreach ($post_types as $post_type) {
        add_meta_box(
            'imaginary_image_field',
            'Images',
            'imaginary_image_field',
            $post_type,
            'normal',
            'default'
        );
    }
}

/**
 * Load added images and button and print to DOM.
 */
function imaginary_image_field() {
    global $post;
    $output = '';

    $image_ids = get_post_meta($post->ID, 'imaginary_images');
    if ($image_ids[0]) {
        foreach($image_ids[0] as $index => $image_id) {
            $image_data = imaginary_get_image_data($image_id, 'thumbnail');

            $output .= '
                <div class="imaginary-image-wrapper">
                    <span class="imaginary-delete-image">delete</span>
                    <img src="' . $image_data['url'] . '">
                    <input type="hidden" name="imaginary_images[]" value="' . $image_id . '">
                </div>
            ';
        }
    }

    print $output . '<button class="button" id="imaginary-image-button"><span>+</span></button>';
}

/**
 * Add style and scripts.
 */
function imaginary_load_js_and_css() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('imaginary', '/wp-content/plugins/imaginary/js/imaginary.js', array('jquery'));
    wp_enqueue_script('imaginary_fileframe', '/wp-content/plugins/imaginary/js/fileframe.js', array('jquery'));
    wp_enqueue_style('imaginary', '/wp-content/plugins/imaginary/css/imaginary.css');
}

/**
 * Save imaginary image data to post.
 *
 * @param int $post_id
 */
function imaginary_save_images($post_id) {
    update_post_meta($post_id, 'imaginary_images', $_POST['imaginary_images']);
}

/**
 * Return all images.
 *
 * @param string $size
 * @return array with all images and all options
 */
function imaginary_images($size = 'thumbnail', $html = false) {
    global $post;
    $images = array();
    $output = '';

    $image_ids = get_post_meta($post->ID, 'imaginary_images');
    if ($image_ids) {
        foreach($image_ids[0] as $index => $image_id) {
            $image = imaginary_get_image_data($image_id, $size);
            $images[$index] = $image;
            $output .= imaginary_get_image_tag($image);
        }
    }

    return $html ? $output : $images;
}

/**
 * Return a specific image.
 *
 * @param int $index
 * @param string $size
 * @param bool $html
 * @return array with all image options
 */
function imaginary_image($index = 0, $size = 'thumbnail', $html = false) {
    $images = imaginary_images($size);
    $image = $images[$index];

    return $html ? imaginary_get_image_tag($image) : $image['url'];
}

/**
 * Shortcode handler.
 *
 * @param array $attributes
 */
function imaginary_shortcode($attributes) {
    extract($attributes);
    print imaginary_image($index, $size, true);
}

/**
 * Generate img tag.
 *
 * @param array $attributes
 */
function imaginary_get_image_tag($image) {
    $tag = '
        <img src="' . $image['url'] . '"
             id="imaginary-image-' . $image['id'] . '"
             width="' . $image['width'] . '"
             height="' . $image['height'] . '">';

    return $tag;
}

/**
 * Get image data.
 *
 * @param array $image
 * @return array
 */
function imaginary_get_image_data($image_id, $size = 'thumbnail') {
    $image = wp_get_attachment_image_src($image_id, $size);
    return array(
        'url' => $image[0],
        'width' => $image[1],
        'height' => $image[2],
        'resized' => $image[3],
        'id' => $image_id
    );
}

/**
 * Register post type fields for settings/media page
 */
function imaginary_create_admin_settings() {
    register_setting('media', 'imaginary_settings_post_types');
    add_settings_field('imaginary_settings_post_types', 'Imaginary', 'imaginary_settings_callback', 'media');
}

/**
 * Render post type setting fields
 */
function imaginary_settings_callback($args) {
    if (!($saved_values = get_option('imaginary_settings_post_types'))) {
        $saved_values = array();
    }

    $checkbox = '<label><input type="checkbox" name="imaginary_settings_post_types[]" value="%s"%s>%s</label><br>';

    $output = '';
    foreach(get_post_types(array('public' => true)) as $post_type) {
        if ($post_type != 'attachment') {
            $selected = in_array($post_type, $saved_values) ? ' checked' : '';
            $output .= sprintf($checkbox, $post_type, $selected, ucfirst($post_type));
        }
    }

    print $output;

    // Show warning on settings page if settings are overridden
    global $db_settings_overridden;
    if ($db_settings_overridden) {
        print '<div id="imaginary-settings-overridden" class="update-nag">Imaginary settings are overridden.</div>';
    }
}
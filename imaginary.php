<?php
/*
 * Plugin Name: Imaginary
 * Version: 1.1.0
 * Plugin URI: http://www.davidajnered.com/
 * Description: Add extra images to your wordpress post types.
 * Author: David Ajnered
 */

/**
 * Define plugin actions.
 */
add_action('init', 'imaginary_init');
add_action('add_meta_boxes', 'imaginary_create_field');
add_action('admin_enqueue_scripts', 'imaginary_load_admin_js_and_css');
add_action('wp_enqueue_scripts', 'imaginary_load_front_js_and_css');
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
function imaginary_init()
{
    // If function exist, override settings
    if (function_exists('imaginary_settings')) {
        global $db_settings_overridden;
        $db_settings_overridden = true;
    }
}

/**
 * Create the extra post type fields.
 */
function imaginary_create_field()
{
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
function imaginary_image_field()
{
    global $post;
    $output = '<div class="imaginary-image-wrapper sortable">';

    $image_ids = get_post_meta($post->ID, 'imaginary_images');
    if ($image_ids[0]) {
        foreach ($image_ids[0] as $index => $image_id) {
            $image_data = imaginary_get_image_data($image_id, 'thumbnail');

            $output .= '
                <div class="imaginary-image attachment selected details">
                    <div class="imaginary-image-menu">
                        <span class="imaginary-image-id check wp-core-ui wp-ui-highlight">#' . ($index + 1) . '</span>
                        <a class="imaginary-image-delete check" href="#" title="Deselect">
                            <div class="media-modal-icon"></div>
                        </a>
                    </div>
                    <img src="' . $image_data['url'] . '">
                    <input type="hidden" name="imaginary_images[]" value="' . $image_id . '">
                </div>
            ';
        }
    }

    $output .= '</div>';
    $output .= '<a id="imaginary-image-add" href="#">Add image</a>';

    print $output;
}

/**
 * Add style and scripts.
 */
function imaginary_load_admin_js_and_css()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('imaginary', '/wp-content/plugins/imaginary/js/imaginary.js', array('jquery'));
    wp_enqueue_script('imaginary_fileframe', '/wp-content/plugins/imaginary/js/fileframe.js', array('jquery'));
    wp_enqueue_style('imaginary', '/wp-content/plugins/imaginary/css/imaginary.css');
}

/**
 * Add style and scripts.
 */
function imaginary_load_front_js_and_css()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery_cycle', '/wp-content/plugins/imaginary/js/jquery.cycle.all.js', array('jquery'));
    wp_enqueue_script('imaginary-front', '/wp-content/plugins/imaginary/js/imaginary-front.js', array('jquery'));
    wp_enqueue_style('imaginary-front', '/wp-content/plugins/imaginary/css/imaginary-front.css');
}

/**
 * Save imaginary image data to post.
 *
 * @param int $post_id
 */
function imaginary_save_images($post_id)
{
    update_post_meta($post_id, 'imaginary_images', $_POST['imaginary_images']);
}

/**
 * Return all images.
 *
 * @param array $options
 * @return array with all images and all options
 */
function imaginary_images($user_options = array())
{
    global $post;


    // Merge user options with default values
    $options =  array_merge(imaginary_get_option_defaults(), $user_options);

    $images = array();
    $image_ids = get_post_meta($post->ID, 'imaginary_images');
    $cycle_class = $options['cycle'] == true ? ' cycle' : '';
    $html = !isset($options['html']) ? true : $options['html'];

    // Build output at the same time as looping data
    $output = '<ul class="imaginary' . $cycle_class . '">';
    if ($image_ids) {
        foreach ($image_ids[0] as $index => $image_id) {
            $image = imaginary_get_image_data($image_id, $options['size']);
            $images[$index] = $image;
            $output .= '<li>' . imaginary_get_image_tag($image) . '</li>';
        }
    }
    $output .= '</ul>';

    // User input decides if output or the raw array with data is returned
    return $html ? $output : $images;
}

/**
 * Return a specific image.
 *
 * @param array $options
 * @return array with all image options
 */
function imaginary($user_options = array())
{
    global $post;

    // Merge user options with default values
    $options =  array_merge(imaginary_get_option_defaults(), $user_options);

    // Some data
    $images = array();
    $image_ids = get_post_meta($post->ID, 'imaginary_images');
    $cycle_class = $options['cycle'] == true ? ' cycle' : '';

    // Build output at the same time as looping data
    $output = '<ul class="imaginary' . $cycle_class . '">';
    if ($image_ids) {
        foreach ($image_ids[0] as $index => $image_id) {
            // If index is set and equal to the one in the loop, or if index is not set at all
            if ((isset($options['index']) && $options['index'] == $index + 1) || !isset($options['index'])) {
                $image = imaginary_get_image_data($image_id, $options['size']);
                $images[$index] = $image;
                $output .= '<li>' . imaginary_get_image_tag($image) . '</li>';
            }
        }
    }
    $output .= '</ul>';

    return $options['html'] ? $output : $images;
}

/**
 * Shortcode handler.
 *
 * @param array $attributes
 */
function imaginary_shortcode($attributes)
{
    if (empty($attributes)) {
        $attributes = array();
    }

    $options = imaginary_get_option_defaults();
    $options['index'] = isset($attributes['index']) ? $attributes['index'] : $options['index'];
    $options['size'] = isset($attributes['size']) ? $attributes['size'] : $options['size'];
    $options['cycle'] = in_array('cycle', $attributes) ? true : $options['cycle'];
    $options['html'] = true;

    // Validate index if set
    if (isset($options['index']) && !is_numeric($options['index'])) {
        trigger_error('Invalid image index', E_USER_ERROR);
    }

    // If index print specific image, else print all images
    if (is_numeric($options['index']) && (int) $options['index'] > 0) {
        print imaginary_image($options, true);
    } else {
        print imaginary_images($options, true);
    }
}

/**
 * Get option default values.
 *
 * @return array
 */
function imaginary_get_option_defaults()
{
    return array(
        'index' => null,
        'size' => 'medium',
        'cycle' => false,
        'html' => true
    );
}

/**
 * Generate img tag.
 *
 * @param array $image
 */
function imaginary_get_image_tag($image)
{
    $html = '
        <img src="' . $image['url'] . '"
             id="imaginary-image-' . $image['id'] . '"
             width="' . $image['width'] . '"
             height="' . $image['height'] . '"
             alt="' . $image['alt_text'] . '">';

    return $html;
}

/**
 * Get image data.
 *
 * @param array $image
 * @return array
 */
function imaginary_get_image_data($image_id, $size = 'thumbnail')
{
    $image = wp_get_attachment_image_src($image_id, $size);
    $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt');
    return array(
        'url' => $image[0],
        'width' => $image[1],
        'height' => $image[2],
        'resized' => $image[3],
        'id' => $image_id,
        'alt_text' => $alt_text[0]
    );
}

/**
 * Register post type fields for settings/media page
 */
function imaginary_create_admin_settings()
{
    register_setting('media', 'imaginary_settings_post_types');
    add_settings_field('imaginary_settings_post_types', 'Imaginary', 'imaginary_settings_callback', 'media');
}

/**
 * Render post type setting fields
 */
function imaginary_settings_callback($args)
{
    if (!($saved_values = get_option('imaginary_settings_post_types'))) {
        $saved_values = array();
    }

    $checkbox = '<label><input type="checkbox" name="imaginary_settings_post_types[]" value="%s"%s>%s</label><br>';

    $output = 'Enable Imaginary for your content types<br><br>';
    foreach (get_post_types(array('public' => true)) as $post_type) {
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

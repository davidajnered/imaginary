=== Plugin Name ===
Contributors: davidajnered
Donate link: http://www.davidajnered.com/
Tags: image, images, duplicatable images, multiple images, slideshow, gallery, extra field, meta, posts, pages, custom post types,
Requires at least: 3.0
Tested up to: 3.9.0
Stable tag: 1.1.0
License: GPLv2 or later

Add extra images to your posts and pages.

== Description ==

Imaginary adds duplicatable image fields to your post types. You select your images in the standard wordpress media modal, and the image ids are saved as meta data to your post. Except for installing the plugin you have to enable it for the post types you want to use it with. You find the setting under settings -> media.

Get all imaginary images for a post. The $size variable returns a specific size and $html determines if an array with the image url's or img tags should be return.
`
imaginary_images($size = 'thumbnail', $html = false)
`

Get a specific imaginary images. Use $index to select the image you want. $html does the same as above. Image index starts at one. Hover the selected images to see the index.
`
imaginary_image($index = 1, $size = 'thumbnail', $html = false)
`

Get a specific image with shortcode.
`
[imaginary index="1" size="large"]
`

You can override settings made in admin by adding a function to functions.php.
`
function imaginary_settings() {
    return array(
        'post_types' => array('post', 'page')
    );
}
`

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enable plugin for your post types in settings -> media
4. Add images to your post or page
5. Add code to your template files or shortcode in your content to see the images

== Frequently Asked Questions ==

= No questions =

== Screenshots ==

= No screenshot =

== Changelog ==

= 1.1 =
* Sort images using drag and drop
* New design copied from media library

= 1.0 =
* First release.
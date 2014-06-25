=== Imaginary ===
Contributors: davidajnered
Donate link: http://www.davidajnered.com/
Tags: image, images, duplicatable images, multiple images, slideshow, gallery, extra field, meta, posts, pages, custom post types,
Requires at least: 3.0
Tested up to: 3.9.0
Stable tag: 1.2.0
License: GPLv2 or later

Add extra images to your posts and pages.

== Description ==
Imaginary adds duplicatable image fields to your post types. You select your images in the standard wordpress media modal, and the image ids are saved as meta data to your post. It's a nice extension to the basic wordpress functionality, and is a great option when migrating for a different platform to wordpress where multiple images have been used.

== Enable Imaginary ==
Except installing the plugin you have to enable it for the post types you want to use it with. You find the setting under settings -> media.

== Usage Imaginary ==
The <b>options</b> array contains variables to control the function output. All values are optional, the plugin have fallbacks for everything.

**(int) index** - index of a specific image. If empty all images are returned.<br>
*Default: null*<br><br>
**(string) size** - name of your image size.<br>
*Default: medium*<br><br>
**(bool) cycle** - display images as a slideshow<br>
*Default: false*<br><br>
**(bool) html** - return html or array with data<br>
*Default: true*<br>

= Simple example =
The example below will show you all selected images as a list. The default values seen above will be used.
`
imaginary();
`

= Get a specific imaginary images =
Use the <b>index</b> option to select the image you want. Image index starts at one. Hover the image when editing your post or page to see the index number. Use any of the other options to customize your use of Imaginary.
`
imaginary(array('index' => 1));
`

= Shortcode =
The simplest way to use shortcode is shown below. It will display all images in a list.
`
[imaginary]
`

When displaying multiple images you can use the build in feature to show them as a slideshow using the cycle attribute.

`
[imaginary cycle]
`

In the example below we display the large version of the image with index one.
`
[imaginary index="1" size="large"]
`

= Slideshow =
Imaginary uses jQuery Cycle. To customize your slideshow, override the function call to cycle in your own javascript file. See http://jquery.malsup.com/cycle for options.


= Override settings =
Add this function to functions.php to override the settings in database.
`
function imaginary_settings() {
    return array(
        'post_types' => array('post', 'page')
    );
}
`

**For more complete and more up to date documentation, see [https://github.com/davidajnered/imaginary](https://github.com/davidajnered/imaginary)**

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

== Upgrade Notice ==
No notices yet.
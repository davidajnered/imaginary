# Imaginary
Imaginary adds duplicatable image fields to your post types. You select your images in the standard wordpress media modal, and the image ids are saved as meta data to your post. It's a nice extension to the basic wordpress functionality, and is a great option when migrating for a different platform to wordpress where multiple images have been used.

### Enable
Except installing the plugin you have to enable it for the post types you want to use it with. You find the setting under settings -> media.

## Usage
The **options** array contains variables to control the function output. All values are optional, the plugin have fallbacks for everything.

* **(int) index**
    index of a specific image. If empty all images are returned.
    *Default: null*
* **(string) size**
    name of your image size.
    *Default: medium*
* **(bool) cycle**
    display images as a slideshow
    *Default: false*
* **(bool) html**.
    return html or array with data
    *Default: true*

### Simple example
The example below will show you all selected images as a list. The default values seen above will be used.
```
imaginary();
```

### Get a specific imaginary images
Use the **index** option to select the image you want. Image index starts at one. Hover the image when editing your post or page to see the index number. Use any of the other options to customize your use of Imaginary.
```
imaginary(array('index' => 1));
```

### Shortcode
The simplest way to use shortcode is shown below. It will display all images in a list.
```
[imaginary]
```

When displaying multiple images you can use the build in feature to show them as a slideshow using the cycle attribute.

```
[imaginary cycle]
```

In the example below we display the large version of the image with index one.
```
[imaginary index="1" size="large"]
```

### Slideshow
Imaginary uses jQuery Cycle. To customize your slideshow, override the function call to cycle in your own javascript file. See http://jquery.malsup.com/cycle for options.


### Override settings
Add this function to functions.php to override the settings in database.
```
function imaginary_settings() {
    return array(
        'post_types' => array('post', 'page')
    );
}
```


## Custom content type
This is an example of how to register a custom content type with imaginary, in this case videos.
This is still experimental, and documentation might not be complete.

```
/**
 * Register all your custom types in an array
 *
 * @return array
 */
function imaginary_register_types()
{
    return array('video');
}

/**
 * imaginary_[type]_field_data
 * This function is called from imaginary core. I expects an id,
 * type and an thumbnail image url in the return array.
 *
 * @return array
 */
function imaginary_video_field_data($id)
{
    $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'thumbnail');

    return array(
        'id' => $id,
        'type' => 'video',
        'image_url' => $image_data[0]
    );
}

/**
 * imaginary_[type]_ajax_field_data
 * Called from modal js when a new item is selected and added.
 * This is called automatically from plugin on page load,
 * only needs this custom function for your custom modal
 */
function imaginary_video_ajax_field_data()
{
    $data = imaginary_video_field_data($_POST['id']);
    $html = imaginary_get_added_image_html($data['image_url'], $data['id'], $data['type']);

    wp_send_json($html);
}
add_action('wp_ajax_imaginary_video_ajax_field_data', 'imaginary_video_ajax_field_data');

/**
 * imaginary_get_[type]_html;
 * return the html you wish to display for your visitors.
 *
 * @return string
 */
function imaginary_get_video_html($id, $options = array())
{
    $shortcode = '[video id="' . $id . '"]';

    return do_shortcode($shortcode);
}

/**
 * imaginary_[type]_modal
 *
 * Print the modal markup and javascript.
 */
function imaginary_video_modal()
{
    // The modal html code
    echo 'your custom modal html';

    // Scripts for controlling the modal
    echo 'your custom modal javascript';
}
```
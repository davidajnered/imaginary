# Imaginary
Imaginary adds duplicatable image fields to your post types. You select your images in the standard wordpress media modal, and the image ids are saved as meta data to your post.

### Enable
Except installing the plugin you have to enable it for the post types you want to use it with. You find the setting under general -> media.

## Usage
Get all imaginary images for a post. The $size variable returns a specific size and $html determines if an array with the image url's or img tags should be return.
```
imaginary_images($size = 'thumbnail', $html = false)
```

Get a specific imaginary images. Use $index to select the image you want. $html does the same as above. Image index starts at one. Hover the selected images to see the index.
```
imaginary_image($index = 1, $size = 'thumbnail', $html = false)
```

Get a specific image with shortcode.
```
[imaginary index="1" size="large"]
```


### Override settings
Add this function to functions.php to override the settings in database.
```
function imaginary_settings() {
    return array(
        'post_types' => array('post', 'page')
    );
}
```
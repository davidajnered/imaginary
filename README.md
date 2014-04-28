# Imaginary
Imaginary adds duplicatable image fields to your post types. You select your images in the standard wordpress media modal, and the image ids are saved as meta data to your post. It's a nice extension to the basic wordpress functionality, and is a great option when migrating for a different platform to wordpress where multiple images have been used.

### Enable
Except installing the plugin you have to enable it for the post types you want to use it with. You find the setting under settings -> media.

## Usage
The <b>options</b> array contains variables to control the function output. All values are optional, the plugin have fallbacks for everything.

<pre>
<b>(int) index</b>
    index of a specific image. If empty all images are returned.
    <i>Default: null</i>
<b>(string) size</b>
    name of your image size.
    <i>Default: medium</i>
<b>(bool) cycle</b>
    display images as a slideshow
    <i>Default: false</i>
<b>(bool) html</b>
    return html or array with data
    <i>Default: true</i>
</pre>

### Simple example
The example below will show you all selected images as a list. The default values seen above will be used.
```
imaginary();
```

### Get a specific imaginary images
Use the <b>index</b> option to select the image you want. Image index starts at one. Hover the image when editing your post or page to see the index number. Use any of the other options to customize your use of Imaginary.
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
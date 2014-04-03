## Imaginary
Info coming soon...


### Override settings
Add this function to functions.php to override the settings in database.
```
function imaginary_settings() {
    return array(
        'post_types' => array(
            'post',
            'page',
        )
    );
}
```
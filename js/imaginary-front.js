jQuery(document).ready(function($) {
    // Init cycle if number of images if more than one
    if ($('ul.imaginary.cycle li').length > 1) {
        $('ul.imaginary.cycle').cycle();
    }
});
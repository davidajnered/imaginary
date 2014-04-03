jQuery(document).ready(function($) {
    $('.imaginary-delete-image').click(function() {
        $(this).parent().remove();
    });
});
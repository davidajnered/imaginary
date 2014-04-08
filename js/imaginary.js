jQuery(document).ready(function($) {
    $(document).on('click', '.imaginary-delete-image', function() {
        $(this).parent().remove();
    });
});
jQuery(document).ready(function($) {
    $(document).on('click', '.imaginary-image-delete', function(event) {
        event.preventDefault();
        $(this).parent().parent().remove();
        reindexImages();
    });

    imaginarySortable();
});

/**
 * Enable drag and drop sort on images
 */
function imaginarySortable() {
    // Re-init sortable
    if (jQuery('.imaginary-image-wrapper').hasClass('ui-sortable')) {
        jQuery('.imaginary-image-wrapper.sortable').sortable('cancel');
    }

    if (jQuery('.imaginary-image-wrapper.sortable').length) {
        jQuery('.imaginary-image-wrapper.sortable').sortable({
            revert: true,
            stop: function() {
                reindexImages();
            }
        });
    }
}

/**
 * Update image index.
 */
function reindexImages() {
    jQuery.each(jQuery('.imaginary-image-wrapper .imaginary-image'), function(index, element) {
        jQuery(element).find('.imaginary-image-id').html('#' + (index + 1));
    });
}
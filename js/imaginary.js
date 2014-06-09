jQuery(document).ready(function($) {
    // On page load, sort images
    imaginaryReindexImages();

    $(document).on('click', '.imaginary-image-delete', function(event) {
        event.preventDefault();
        $(this).parent().parent().remove();
        imaginaryReindexImages();
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
                imaginaryReindexImages();
            }
        });
    }
}

/**
 * Update image index.
 */
function imaginaryReindexImages() {
    jQuery.each(jQuery('.imaginary-image-wrapper .imaginary-image'), function(index, element) {
        jQuery(element).find('.imaginary-image-id').html('#' + (index + 1));
        jQuery(element).find('.imaginary-sort-order').val((index + 1));
    });
}

/**
 * Added image html to edit page in admin.
 * Javascript equivalent to imaginary_get_added_image_html in imaginary.php.
 */
function imaginaryAddedImageHtml(url, id, type) {
    var output = '' +
        '<div class="imaginary-image attachment selected details">' +
            '<div class="imaginary-image-menu">' +
                '<span class="imaginary-image-id check wp-core-ui wp-ui-highlight">#</span>' +
                '<a class="imaginary-image-delete check" href="#" title="Deselect"><div class="media-modal-icon"></div></a>' +
            '</div>' +
            '<img src="' + url + '">' +
            '<input type="hidden" name="imaginary[' + id + '][id]" value="' + id + '">' +
            '<input type="hidden" name="imaginary[' + id + '][sort_order]">' +
            '<input type="hidden" name="imaginary[' + id + '][type]" value="' + type + '">' +
        '</div>';

    // Add to DOM
    jQuery('.imaginary-image-wrapper').append(output);

    // Make new image(s) sortable
    imaginarySortable();
    imaginaryReindexImages();

}
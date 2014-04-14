/**
 * Inspired and modified from tutorial below.
 * http://mikejolley.com/2012/12/using-the-new-wordpress-3-5-media-uploader-in-plugins/
 */
jQuery(document).ready(function($) {

    if (wp.media != undefined) {
        // Uploading files
        var file_frame;
        var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
        var set_to_post_id = 10; // Set this

        $('#imaginary-image-add').live('click', function(event) {
            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (file_frame) {
                // Set the post ID to what we want
                file_frame.uploader.uploader.param('post_id', set_to_post_id);
                // Open frame
                file_frame.open();
                return;
            } else {
                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: jQuery(this).data('uploader_title'),
                button: {
                    text: jQuery(this).data('uploader_button_text'),
                },
                multiple: false  // Set to true to allow multiple files to be selected
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function() {
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();

                // Calculate index
                var index = $('.imaginary-image').length + 1;

                // Insert image to post type
                var output = '' +
                    '<div class="imaginary-image attachment selected details">' +
                        '<div class="imaginary-image-menu">' +
                            '<span class="imaginary-image-id check wp-core-ui wp-ui-highlight">#' + index + '</span>' +
                            '<a class="imaginary-image-delete check" href="#" title="Deselect"><div class="media-modal-icon"></div></a>' +
                        '</div>' +
                        '<img src="' + attachment.sizes.thumbnail.url + '">' +
                        '<input type="hidden" name="imaginary_images[]" value="' + attachment.id + '">' +
                    '</div>';

                $('.imaginary-image-wrapper').append(output);

                imaginarySortable();

                // Restore the main post ID
                wp.media.model.settings.post.id = wp_media_post_id;
            });

            // Finally, open the modal
            file_frame.open();
        });

        // Restore the main ID when the add media button is pressed
        jQuery('a.add_media').on('click', function() {
            wp.media.model.settings.post.id = wp_media_post_id;
        });
    }
});

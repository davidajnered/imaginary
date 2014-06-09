/**
 * Code from https://github.com/ocean90/media-modal-demo
 */

var ds = ds || {};

/**
 * Demo 1
 */
( function( $ ) {
    var media;

    /**
     * Creates the media object.
     *
     * `ds` is the global variable which will be extend with the media object.
     * Because of this it's possible to call the object from the console too!
     *
     * `media` is just a reference/shortcut for `ds.media`
     */
    ds.media = media = {
        // Set the button ID
        buttonId: '#imaginary-image-add',
        // Set the container where the attachment details will be shown
        detailsContainerId: '#attachment-details',

        /**
         * This is the initialiser.
         */
        init: function() {
            // Add the event handler to our button to open the media modal
            $( this.buttonId ).on( 'click', this.openMediaDialog );
        },

        /**
         * This function which will be called, if the user clicks the button.
         *
         * Here we adjuste our own media frame, which will be declared only once.
         */
        openMediaDialog: function( e ) {
            // Check if the frame is already declared.
            // If true, open the frame.
            if ( this._frame ) {
                this._frame.open();
                return;
            }

            /**
             * Creates the frame which is based on wp.media().
             *
             * wp.media() handles the default media experience. It automatically creates
             * and opens a media frame, and returns the result.
             * wp.media() can take some attributes.
             * In this demo we make use of:
             *  - title: The title of the frame
             *  - button
             *     - text: The string of the select button in the toolbar (bottom)
             *  - multiple: If false, only one media item can be selected at once
             *  - library
             *     - type: Declares which media mime types will be displayed in the library
             *             Examples: `image` for images, `audio` for audio, `video` for video
             *
             * Note: When the frame is generated once, you can open the dialog from the JS
             * console too: ds.media.frame.open() or ds.media.frame.close()
             */
            this._frame = media.frame = wp.media( {
                // Custom attributes
                title: $( this ).data( 'title' ),
                button: {
                    text: $( this ).data( 'buttonText' )
                },
                multiple: true,
                library: {
                    type: 'image'
                }
            } );

            /**
             * Handles the ready event.
             *
             * The frame triggers some events on special things. For example when the frame
             * is opened/closed or is ready.
             * The ready event will be fired once when the frame is completly initialised.
             */
            this._frame.on( 'ready', function() {
                // Here we can add a custom class to our media modal.
                // .media-modal doesn't exists before the frame is
                // completly initialised.
                $( '.media-modal' ).addClass( 'no-sidebar' );
            } );

            /**
             * Handles select button function.
             *
             * Our frame has currently one state, the library state.
             * When you have selected a media item and click the select button
             * the frame will close. Now it's the time to get the selected attachment.
             */
            this._frame.state( 'library' ).on( 'select', function() {
                // Get the selected attachments.
                $.each(this.get( 'selection' ).models, function(index, attachment) {
                    // Call the function which will output the attachment details
                    media.handleMediaAttachment( attachment );
                });

            } );

            /**
             * Opens the modal.
             *
             * Now the frame is adjusted and we can open it.
             */
            this._frame.open();
        },

        /**
         * Handles the attachment details output
         *
         * The attachment is a model and so we can get access to each attachment
         * attribute: attachment.get( key )
         */
        handleMediaAttachment: function( attachment ) {
            var attachment = attachment.attributes,
                index = $('.imaginary-image').length + 1,
                url = attachment.sizes != undefined ? attachment.sizes.thumbnail.url : attachment.url;

            // Insert image to post type
            imaginaryAddedImageHtml(url, attachment.id, 'image');
        }
    };

    $( document ).ready( function() {
        /**
         * Inits our media object.
         */
        media.init();
    } );
} )( jQuery );
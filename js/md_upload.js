/**
 * Callback function for the 'click' event of the 'Set Footer Image'
 * anchor in its meta box.
 *
 * Displays the media uploader for selecting an image.
 *
 * @since 0.1.0
 */
function renderMediaUploader($,logo) {
    'use strict';
 
    var file_frame, image_data,json;
 
    /**
     * If an instance of file_frame already exists, then we can open it
     * rather than creating a new instance.
     */
    if ( undefined !== file_frame ) {
 
        file_frame.open();
        return;
 
    }
 
    /**
     * If we're this far, then an instance does not exist, so we need to
     * create our own.
     *
     * Here, use the wp.media library to define the settings of the Media
     * Uploader. We're opting to use the 'post' frame which is a template
     * defined in WordPress core and are initializing the file frame
     * with the 'insert' state.
     *
     * We're also not allowing the user to select more than one image.
     */
    file_frame = wp.media.frames.file_frame = wp.media({
        frame:    'post',
        state:    'insert',
        multiple: false
    });
 
    /**
     * Setup an event handler for what to do when an image has been
     * selected.
     *
     * Since we're using the 'view' state when initializing
     * the file_frame, we need to make sure that the handler is attached
     * to the insert event.
     */
    file_frame.on( 'insert', function() {
 
        /**
         * We'll cover this in the next version.
         */

         json = file_frame.state().get( 'selection' ).first().toJSON();
         
         if(0 > $.trim(json.url.length)){
         	return;
         }



         $('.' +logo + '_img')
         .find('img')
         	.attr('src', json.url)
         	.parent()
         	.removeClass('hidden');

         $('#'+logo).val(json.url);

         $('#set-' + logo ).addClass('hidden');
         $('#remove-' + logo ).removeClass('hidden');

    });
 
    // Now display the actual file_frame
    file_frame.open();
 
}

function removeLogo($,logo){
	$('.' + logo + '_img').addClass('hidden');
	$('#'+logo).val('');
	$('#set-' + logo ).removeClass('hidden');
    $('#remove-' + logo ).addClass('hidden');
}
 
(function( $ ) {
    'use strict';
 
    $(function() {
        $( '.set-logo').on( 'click', function( evt ) {
 
            // Stop the anchor's default behavior
            evt.preventDefault();

            var logo = $(this).data('logo');

            // Display the media uploader
            renderMediaUploader($,logo);
 
        });
 
	    $('.remove-logo').on( 'click', function( evt ) {
            //evt.preventDefault();
	    	var logo = $(this).data('logo');
	     	removeLogo($,logo);
	    });

        $('#md_google_maps').on('keyup', function(event) {
            event.preventDefault();
           if($(this).val() != ''){
                var src = $('.localization iframe').attr('src');
                var pos = src.indexOf('q=');
                var cutsrc = src.substring(0,pos);

                src = cutsrc + 'q=' + $(this).val();
                
                $('.localization iframe').attr('src',src) ;
            }
        });

    });

 
})( jQuery );
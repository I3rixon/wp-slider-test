jQuery(document).ready(function ($) {
    $('.slider-item').on('click', function () {
        const postId = $(this).data('id');
        $('#slider-modal').fadeIn();
        $('#modal-description').html("Loading..."); 
        $.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'load_slider_description',
                post_id: postId,
            },
            success: function (response) {
                if (response.success) {
                    $('#modal-description').html(response.data.description);                    
                } else {                    
                    $('#modal-description').html(response.data.message);
                }
            },
        });
    });

    $('.close-modal').on('click', function () {
        $('#slider-modal').fadeOut();
    });
});

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

    const swiper = new Swiper('.swiper-container', {
        slidesPerView: 1,
        spaceBetween: 50,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
                spaceBetween: 15,

              },
            // Configuration for tablets
            768: {
                slidesPerView: 2, 
                spaceBetween: 25,
            },
            // Configuration for desktop
            1024: {
                slidesPerView: 4, 
                spaceBetween: 50,
            }
        },
    });
    const modal = $('#slider-modal');
    $(window).on('click', function (e) {
        if ($(e.target).is(modal)) {
            modal.hide();
        }
    });
});

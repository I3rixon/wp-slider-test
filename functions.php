<?php

add_action('after_setup_theme', 'mytheme_theme_setup');

if ( ! function_exists( 'mytheme_theme_setup' ) ){
    function mytheme_theme_setup(){
        add_action( 'wp_enqueue_scripts', 'mytheme_scripts');
    }
}

if ( ! function_exists( 'mytheme_scripts' ) ){
    function mytheme_scripts() {
        // CSS
        wp_enqueue_style( 'theme_css', get_template_directory_uri().'/css/main.css' );
        wp_enqueue_style( 'custom_css', get_template_directory_uri().'/css/custom.css' );

        // Scripts
        wp_enqueue_script( 'theme_js', get_template_directory_uri().'/js/libs/jquery-3.6.0.min.js', array( 'jquery'), '1.0.0', true );
        wp_enqueue_script( 'theme_js_2', get_template_directory_uri().'/js/libs/jquery.scrollbar.min.js', array( 'jquery'), '1.0.0', true );
        wp_enqueue_script( 'theme_js_3', get_template_directory_uri().'/js/libs/ion.rangeSlider.min.js', array( 'jquery'), '1.0.0', true );
        wp_enqueue_script( 'theme_js_4', get_template_directory_uri().'/js/libs/jquery.magnific-popup.min.js', array( 'jquery'), '1.0.0', true );
        wp_enqueue_script( 'theme_js_5', get_template_directory_uri().'/js/libs/swiper-bundle.min.js', array( 'jquery'), '1.0.0', true );
        wp_enqueue_script( 'theme_js_6', get_template_directory_uri().'/js/main.js', array( 'jquery'), '1.0.0', true );        

        wp_localize_script( 'theme_js_6', 'ajax_object', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'directory_uri' => get_template_directory_uri(),
            'bloginfo_url' => get_bloginfo('url'),
        ));
    }
}

/* Add custom post type */
function register_slider_post_type() {
    register_post_type('slider', [
        'labels' => [
            'name' => 'Слайдери',
            'singular_name' => 'Слайдер',
            'add_new' => 'Додати новий слайд',
            'add_new_item' => 'Додати новий слайд',
            'edit_item' => 'Редагувати слайд',
            'new_item' => 'Новий слайд',
            'view_item' => 'Переглянути слайд',
            'search_items' => 'Шукати слайди',
            'not_found' => 'Слайди не знайдено',
            'not_found_in_trash' => 'У кошику слайдів не знайдено',
        ],
        'public' => true,
        'supports' => ['title', 'thumbnail', 'editor'],
        'menu_icon' => 'dashicons-images-alt2',
    ]);
}
add_action('init', 'register_slider_post_type');

add_theme_support('post-thumbnails');

function display_slider_shortcode() {
    $args = [
        'post_type' => 'slider',
        'posts_per_page' => 10,
    ];
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
        ?>
        <h2>Explore by Room: Tailored Furniture Selections</h2>
        <div class="swiper">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="slider-item swiper-slide" data-id="<?php the_ID(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="slider-image">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="slider-title"><?php the_title(); ?></div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Swiper Navigation Buttons -->
                <div class="swiper-button-next">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.59 8.09383L13.17 12.6738L8.59 17.2638L10 18.6738L16 12.6738L10 6.67383L8.59 8.09383Z" fill="#222222" />
                    </svg>
                </div>
                <div class="swiper-button-prev">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.41 17.2538L10.83 12.6738L15.41 8.08383L14 6.67383L8 12.6738L14 18.6738L15.41 17.2538Z" fill="#222222" />
                    </svg>
                </div>

                <!-- Swiper Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <div id="slider-modal" style="display: none;">
            <div class="modal-content">
                <div class="popup-box">
                    <span class="close-modal">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 1L1 13M1 1L13 13" stroke="#997F5A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <div class="popup-title">Description</div>
                    <div class="popup-divider"></div>
                    <div class="popup-text" id="modal-description"></div>
                </div>
            </div>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
    return '<p>Слайди не знайдено.</p>';
}
add_shortcode('slider', 'display_slider_shortcode');


function load_slider_description() {
    $post_id = intval($_POST['post_id']);
    if ($post_id) {
        $post = get_post($post_id);
        if ($post) {
            wp_send_json_success(['description' => wpautop($post->post_content)]);
        }
    }
    wp_send_json_error(['message' => 'Слайд не знайдено']);
}
add_action('wp_ajax_load_slider_description', 'load_slider_description');
add_action('wp_ajax_nopriv_load_slider_description', 'load_slider_description');

<?php
/**
 * Plugin Name: Volunteer Spotlight
 * Plugin URI: https://smysolutions.us/
 * Description: Showcase your amazing volunteers with beautiful spotlight cards displayed in a slider. Use shortcode [volunteer_spotlight] to display.
 * Version: 1.4.0
 * Author: Muhammad Mehdi
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('VSP_VERSION', '1.4.0');
define('VSP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VSP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('VSP_FA_URL', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');

/**
 * ========================================
 * 1. REGISTER CUSTOM POST TYPE
 * ========================================
 */
function vsp_register_post_type() {
    $labels = array(
        'name'               => 'Volunteer Spotlights',
        'singular_name'      => 'Volunteer Spotlight',
        'menu_name'          => 'Vol. Spotlight',
        'add_new'            => 'Add Spotlight',
        'add_new_item'       => 'Add New Spotlight',
        'edit_item'          => 'Edit Spotlight',
        'new_item'           => 'New Spotlight',
        'view_item'          => 'View Spotlight',
        'search_items'       => 'Search Spotlights',
        'not_found'          => 'No spotlights found',
        'not_found_in_trash' => 'No spotlights found in Trash',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-heart',
        'supports'           => array('title', 'thumbnail'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'rewrite'            => false,
    );

    register_post_type('volunteer_spotlight', $args);
}
add_action('init', 'vsp_register_post_type');

/**
 * ========================================
 * 2. ADD META BOXES
 * ========================================
 */
function vsp_add_meta_boxes() {
    add_meta_box(
        'vsp_details',
        'Volunteer Details',
        'vsp_details_callback',
        'volunteer_spotlight',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'vsp_add_meta_boxes');

function vsp_details_callback($post) {
    wp_nonce_field('vsp_save_details', 'vsp_details_nonce');

    $designation = get_post_meta($post->ID, '_vsp_designation', true);
    $description = get_post_meta($post->ID, '_vsp_description', true);

    // Social profiles
    $social_facebook  = get_post_meta($post->ID, '_vsp_social_facebook',  true);
    $social_instagram = get_post_meta($post->ID, '_vsp_social_instagram', true);
    $social_twitter   = get_post_meta($post->ID, '_vsp_social_twitter',   true);
    $social_linkedin  = get_post_meta($post->ID, '_vsp_social_linkedin',  true);
    $social_youtube   = get_post_meta($post->ID, '_vsp_social_youtube',   true);
    ?>
    <div class="vsp-admin-fields">
        <div class="vsp-field-group">
            <label for="vsp_designation"><strong>Designation / Role</strong></label>
            <input type="text" id="vsp_designation" name="vsp_designation" 
                   value="<?php echo esc_attr($designation); ?>" 
                   placeholder="e.g. Community Outreach Volunteer" 
                   class="widefat" />
            <p class="description">The volunteer's role or title (displayed in red below their name).</p>
        </div>

        <div class="vsp-field-group" style="margin-top: 20px;">
            <label for="vsp_description"><strong>Description / Quote</strong></label>
            <?php
            wp_editor($description, 'vsp_description', array(
                'textarea_name' => 'vsp_description',
                'textarea_rows' => 6,
                'media_buttons' => false,
                'teeny'         => true,
                'quicktags'     => true,
            ));
            ?>
            <p class="description">A short description or quote about the volunteer's contributions.</p>
        </div>

        <div class="vsp-field-group" style="margin-top: 20px;">
            <label><strong><i class="fa-solid fa-share-nodes"></i> Social Profiles</strong></label>
            <p class="description" style="margin-bottom:10px;">Add the volunteer's social profile URLs. Social icons will appear on the spotlight card for any accounts you fill in.</p>
            <table class="form-table" style="margin:0;">
                <tr>
                    <th style="width:140px;padding:6px 0;"><i class="fa-brands fa-facebook" style="color:#1877F2;margin-right:6px;"></i>Facebook</th>
                    <td style="padding:6px 0;"><input type="url" name="vsp_social_facebook" value="<?php echo esc_attr($social_facebook); ?>" placeholder="https://facebook.com/username" class="widefat" /></td>
                </tr>
                <tr>
                    <th style="padding:6px 0;"><i class="fa-brands fa-instagram" style="color:#E1306C;margin-right:6px;"></i>Instagram</th>
                    <td style="padding:6px 0;"><input type="url" name="vsp_social_instagram" value="<?php echo esc_attr($social_instagram); ?>" placeholder="https://instagram.com/username" class="widefat" /></td>
                </tr>
                <tr>
                    <th style="padding:6px 0;"><i class="fa-brands fa-x-twitter" style="color:#000;margin-right:6px;"></i>X / Twitter</th>
                    <td style="padding:6px 0;"><input type="url" name="vsp_social_twitter" value="<?php echo esc_attr($social_twitter); ?>" placeholder="https://x.com/username" class="widefat" /></td>
                </tr>
                <tr>
                    <th style="padding:6px 0;"><i class="fa-brands fa-linkedin" style="color:#0A66C2;margin-right:6px;"></i>LinkedIn</th>
                    <td style="padding:6px 0;"><input type="url" name="vsp_social_linkedin" value="<?php echo esc_attr($social_linkedin); ?>" placeholder="https://linkedin.com/in/username" class="widefat" /></td>
                </tr>
                <tr>
                    <th style="padding:6px 0;"><i class="fa-brands fa-youtube" style="color:#FF0000;margin-right:6px;"></i>YouTube</th>
                    <td style="padding:6px 0;"><input type="url" name="vsp_social_youtube" value="<?php echo esc_attr($social_youtube); ?>" placeholder="https://youtube.com/@channel" class="widefat" /></td>
                </tr>
            </table>
        </div>

        <div class="vsp-field-group" style="margin-top: 20px; padding: 15px; background: #f0f0f1; border-radius: 8px;">
            <p><strong><i class="fa-solid fa-image"></i> Photo:</strong> Use the <em>"Set featured image"</em> option in the right sidebar to upload the volunteer's photo.</p>
            <p><strong><i class="fa-solid fa-user-tag"></i> Name:</strong> Use the <em>"Title"</em> field above for the volunteer's name.</p>
        </div>

        <div class="vsp-field-group" style="margin-top: 20px; padding: 15px; background: #fef3f3; border-left: 4px solid #e53935; border-radius: 4px;">
            <p><strong>Shortcode:</strong> <code>[volunteer_spotlight]</code></p>
            <p class="description" style="margin-top: 5px;">
                Options: <code>[volunteer_spotlight limit="6" autoplay="true" speed="4000"]</code>
            </p>
        </div>
    </div>
    <?php
}

/**
 * ========================================
 * 3. SAVE META DATA
 * ========================================
 */
function vsp_save_meta($post_id) {
    // Security checks
    if (!isset($_POST['vsp_details_nonce']) || !wp_verify_nonce($_POST['vsp_details_nonce'], 'vsp_save_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save designation
    if (isset($_POST['vsp_designation'])) {
        update_post_meta($post_id, '_vsp_designation', sanitize_text_field($_POST['vsp_designation']));
    }

    // Save description
    if (isset($_POST['vsp_description'])) {
        update_post_meta($post_id, '_vsp_description', wp_kses_post($_POST['vsp_description']));
    }

    // Save social profiles
    $social_fields = array('vsp_social_facebook', 'vsp_social_instagram', 'vsp_social_twitter', 'vsp_social_linkedin', 'vsp_social_youtube');
    foreach ($social_fields as $field) {
        $meta_key = '_' . $field;
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta_key, esc_url_raw($_POST[$field]));
        }
    }
}
add_action('save_post_volunteer_spotlight', 'vsp_save_meta');

/**
 * ========================================
 * 4. ADMIN COLUMNS
 * ========================================
 */
function vsp_custom_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['vsp_photo'] = 'Photo';
    $new_columns['title'] = 'Name';
    $new_columns['vsp_designation'] = 'Designation';
    $new_columns['date'] = 'Date';
    return $new_columns;
}
add_filter('manage_volunteer_spotlight_posts_columns', 'vsp_custom_columns');

function vsp_custom_column_content($column, $post_id) {
    switch ($column) {
        case 'vsp_photo':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(50, 50), array('style' => 'border-radius: 50%; object-fit: cover;'));
            } else {
                echo '<span class="dashicons dashicons-format-image" style="font-size: 30px; color: #ccc;"></span>';
            }
            break;
        case 'vsp_designation':
            echo esc_html(get_post_meta($post_id, '_vsp_designation', true));
            break;
    }
}
add_action('manage_volunteer_spotlight_posts_custom_column', 'vsp_custom_column_content', 10, 2);

/**
 * ========================================
 * 5. ENQUEUE ADMIN STYLES
 * ========================================
 */
function vsp_admin_styles($hook) {
    global $post_type;
    if ($post_type === 'volunteer_spotlight') {
        wp_enqueue_style('vsp-fontawesome', VSP_FA_URL, array(), '6.5.1');
        wp_enqueue_style('vsp-admin-style', VSP_PLUGIN_URL . 'admin/css/admin-style.css', array(), VSP_VERSION);
    }
}
add_action('admin_enqueue_scripts', 'vsp_admin_styles');

/**
 * ========================================
 * 6. ENQUEUE FRONTEND ASSETS
 * ========================================
 */
function vsp_frontend_assets() {
    // Font Awesome
    wp_enqueue_style('vsp-fontawesome', VSP_FA_URL, array(), '6.5.1');

    // Swiper CSS & JS (use unique handles to avoid conflicts with other plugins)
    if ( ! wp_style_is( 'swiper-css', 'registered' ) ) {
        wp_register_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0' );
    }
    wp_enqueue_style( 'swiper-css' );

    if ( ! wp_script_is( 'swiper-js', 'registered' ) ) {
        wp_register_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true );
    }
    wp_enqueue_script( 'swiper-js' );
}
add_action('wp_enqueue_scripts', 'vsp_frontend_assets', 20);

/**
 * ========================================
 * 7. INLINE CSS HELPER
 * ========================================
 */
function vsp_get_inline_css() {
    return '
:root{--vsp-red:#E53935;--vsp-red-dark:#C62828;--vsp-red-light:#FFEBEE;--vsp-white:#FFFFFF;--vsp-gray-light:#F5F5F5;--vsp-gray:#757575;--vsp-dark:#212121;--vsp-card-radius:16px;--vsp-shadow:0 10px 40px rgba(0,0,0,.12);--vsp-shadow-hover:0 20px 60px rgba(0,0,0,.2);--vsp-font:"Segoe UI",-apple-system,BlinkMacSystemFont,sans-serif}
.vsp-slider-wrapper{max-width:1200px;margin:40px auto;padding:20px 10px;font-family:var(--vsp-font)}
.vsp-swiper{padding-bottom:60px!important;overflow:hidden}
.vsp-card{position:relative;background:var(--vsp-white);border-radius:var(--vsp-card-radius);box-shadow:var(--vsp-shadow);overflow:hidden;transition:box-shadow .3s ease,transform .3s ease}
.vsp-card:hover{box-shadow:var(--vsp-shadow-hover);transform:translateY(-4px)}
.vsp-card-inner{display:flex!important;flex-direction:row!important;min-height:350px}
.vsp-decor-dots{position:absolute;top:20px;right:20px;width:60px;height:60px;z-index:2;background-image:radial-gradient(circle,var(--vsp-red) 1.5px,transparent 1.5px);background-size:10px 10px;opacity:.35}
.vsp-decor-triangle{position:absolute;bottom:0;right:0;width:120px;height:120px;z-index:1;overflow:hidden}
.vsp-decor-triangle::before{content:"";position:absolute;bottom:-30px;right:-30px;width:120px;height:120px;border:3px solid var(--vsp-red-light);transform:rotate(45deg);opacity:.5}
.vsp-decor-triangle::after{content:"";position:absolute;bottom:-15px;right:-15px;width:90px;height:90px;border:3px solid var(--vsp-red-light);transform:rotate(45deg);opacity:.3}
.vsp-card-photo{position:relative;flex:0 0 45%!important;max-width:45%!important;overflow:hidden;min-height:300px;border-radius:var(--vsp-card-radius) 0 0 var(--vsp-card-radius)}
.vsp-card-photo img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s ease}
.vsp-card:hover .vsp-card-photo img{transform:scale(1.05)}
.vsp-photo-placeholder{width:100%;height:100%;min-height:300px;display:flex;align-items:center;justify-content:center;background:var(--vsp-gray-light);color:#ccc}
.vsp-photo-placeholder svg{width:80px;height:80px}
.vsp-heart-icon{position:absolute;top:16px;left:16px;width:40px;height:40px;background:rgba(255,255,255,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--vsp-red);box-shadow:0 2px 10px rgba(0,0,0,.1);z-index:3;animation:vsp-pulse 2s ease-in-out infinite}
@keyframes vsp-pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.1)}}
.vsp-card-content{flex:1!important;padding:35px 30px 30px;display:flex;flex-direction:column;justify-content:center;position:relative;z-index:2}
.vsp-badge{display:inline-flex!important;align-items:center;gap:6px;background:var(--vsp-red)!important;color:var(--vsp-white)!important;padding:6px 16px;border-radius:20px;font-size:13px;font-weight:600;width:fit-content;margin-bottom:16px;letter-spacing:.3px}
.vsp-badge-star{font-size:14px}
.vsp-name{font-size:28px!important;font-weight:800!important;color:var(--vsp-dark)!important;margin:0 0 6px!important;line-height:1.2;letter-spacing:-.5px}
.vsp-designation{font-size:16px;font-weight:600;color:var(--vsp-red)!important;margin:0 0 16px!important;letter-spacing:.2px}
.vsp-description{font-size:14px;line-height:1.7;color:var(--vsp-gray);margin-bottom:20px;display:-webkit-box;-webkit-line-clamp:5;-webkit-box-orient:vertical;overflow:hidden}
.vsp-description p{margin:0 0 8px}
.vsp-social-links{display:flex!important;align-items:center;gap:10px;margin-top:8px;flex-wrap:wrap}
.vsp-social-icon{display:inline-flex!important;align-items:center;justify-content:center;width:40px;height:40px;border-radius:50%;background:#f5f5f5;color:#555!important;font-size:16px;text-decoration:none!important;transition:all .3s ease;border:2px solid transparent}
.vsp-social-icon:hover{transform:translateY(-3px);text-decoration:none!important;color:#fff!important}
.vsp-social-facebook:hover{background:#1877F2!important;border-color:#1877F2;color:#fff!important}
.vsp-social-instagram:hover{background:radial-gradient(circle at 30% 107%,#fdf497 0%,#fd5949 45%,#d6249f 60%,#285AEB 90%)!important;border-color:#d6249f;color:#fff!important}
.vsp-social-twitter:hover{background:#000!important;border-color:#000;color:#fff!important}
.vsp-social-linkedin:hover{background:#0A66C2!important;border-color:#0A66C2;color:#fff!important}
.vsp-social-youtube:hover{background:#FF0000!important;border-color:#FF0000;color:#fff!important}
.vsp-nav-container{display:flex;align-items:center;justify-content:center;gap:20px;margin-top:10px}
.vsp-swiper .swiper-button-prev,.vsp-swiper .swiper-button-next{position:static;width:44px;height:44px;background:var(--vsp-white);border:2px solid var(--vsp-red);border-radius:50%;color:var(--vsp-red);transition:all .3s ease;margin:0}
.vsp-swiper .swiper-button-prev:hover,.vsp-swiper .swiper-button-next:hover{background:var(--vsp-red);color:var(--vsp-white)}
.vsp-swiper .swiper-button-prev::after,.vsp-swiper .swiper-button-next::after{font-size:16px;font-weight:bold}
.vsp-swiper .swiper-pagination{position:static;display:flex;gap:8px;justify-content:center}
.vsp-swiper .swiper-pagination-bullet{width:10px;height:10px;background:#ddd;opacity:1;transition:all .3s ease}
.vsp-swiper .swiper-pagination-bullet-active{background:var(--vsp-red);width:28px;border-radius:5px}
.vsp-no-results{text-align:center;padding:40px;color:var(--vsp-gray);font-family:var(--vsp-font);font-size:16px}
.vsp-swiper .swiper-slide{opacity:.4;transition:opacity .4s ease}
.vsp-swiper .swiper-slide-active{opacity:1}
.vsp-card::after{content:"";position:absolute;bottom:0;left:0;right:0;height:4px;background:linear-gradient(90deg,var(--vsp-red),var(--vsp-red-dark));opacity:0;transition:opacity .3s ease}
.vsp-card:hover::after{opacity:1}
@media(max-width:768px){.vsp-card-inner{flex-direction:column!important;min-height:auto}.vsp-card-photo{flex:none!important;max-width:100%!important;height:280px;min-height:280px}.vsp-card-content{padding:24px 20px}.vsp-name{font-size:22px!important}.vsp-designation{font-size:14px}.vsp-description{-webkit-line-clamp:4}.vsp-decor-dots{display:none}.vsp-slider-wrapper{padding:10px 5px;margin:20px auto}}
@media(max-width:480px){.vsp-card-photo{height:220px;min-height:220px}.vsp-card-content{padding:20px 16px}.vsp-name{font-size:20px!important}.vsp-badge{font-size:11px;padding:5px 12px}.vsp-read-more{padding:8px 18px!important;font-size:13px}.vsp-swiper .swiper-button-prev,.vsp-swiper .swiper-button-next{width:36px;height:36px}.vsp-swiper .swiper-button-prev::after,.vsp-swiper .swiper-button-next::after{font-size:13px}}
';
}

/**
 * ========================================
 * 8. INLINE JS HELPER
 * ========================================
 */
function vsp_get_inline_js() {
    return '
document.addEventListener("DOMContentLoaded", function() {
    var settings = window.vspSettings || {};
    var autoplay = settings.autoplay !== false;
    var speed = settings.speed || 4000;
    var swiperConfig = {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 30,
        grabCursor: true,
        speed: 600,
        pagination: { el: ".vsp-pagination", clickable: true },
        navigation: { nextEl: ".vsp-nav-next", prevEl: ".vsp-nav-prev" },
        keyboard: { enabled: true, onlyInViewport: true }
    };
    if (autoplay) {
        swiperConfig.autoplay = { delay: speed, disableOnInteraction: false, pauseOnMouseEnter: true };
    }
    if (typeof Swiper !== "undefined") {
        new Swiper(".vsp-swiper", swiperConfig);
    } else {
        setTimeout(function() {
            if (typeof Swiper !== "undefined") { new Swiper(".vsp-swiper", swiperConfig); }
        }, 1500);
    }
});
';
}

/**
 * ========================================
 * 9. SHORTCODE [volunteer_spotlight]
 * ========================================
 */
function vsp_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit'    => -1,
        'autoplay' => 'true',
        'speed'    => 4000,
        'order'    => 'DESC',
    ), $atts, 'volunteer_spotlight');

    // No extra enqueues needed, we will inline
    
    // Pass settings to JS (we'll use this in our inlined script)
    $settings = array(
        'autoplay' => $atts['autoplay'] === 'true',
        'speed'    => intval($atts['speed']),
    );

    // Query spotlights
    $query = new WP_Query(array(
        'post_type'      => 'volunteer_spotlight',
        'posts_per_page' => intval($atts['limit']),
        'order'          => $atts['order'],
        'orderby'        => 'date',
        'post_status'    => 'publish',
    ));

    if (!$query->have_posts()) {
        return '<p class="vsp-no-results">No volunteer spotlights to display yet.</p>';
    }

    ob_start();

    // Hardcoded inline CSS — zero file-path dependency
    echo '<style id="vsp-inline-styles">' . vsp_get_inline_css() . '</style>';
    ?>
    <div class="vsp-slider-wrapper">
        <div class="swiper vsp-swiper">
            <div class="swiper-wrapper">
                <?php while ($query->have_posts()) : $query->the_post(); 
                    $designation = get_post_meta(get_the_ID(), '_vsp_designation', true);
                    $description = get_post_meta(get_the_ID(), '_vsp_description', true);
                    $photo_url   = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    $name        = get_the_title();
                ?>
                <div class="swiper-slide">
                    <div class="vsp-card">
                        <!-- Decorative Elements -->
                        <div class="vsp-decor-dots"></div>
                        <div class="vsp-decor-triangle"></div>
                        
                        <div class="vsp-card-inner">
                            <!-- Photo Side -->
                            <div class="vsp-card-photo">
                                <?php if ($photo_url) : ?>
                                    <img src="<?php echo esc_url($photo_url); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy" />
                                <?php else : ?>
                                    <div class="vsp-photo-placeholder">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 12C14.21 12 16 10.21 16 8C16 5.79 14.21 4 12 4C9.79 4 8 5.79 8 8C8 10.21 9.79 12 12 12ZM12 14C9.33 14 4 15.34 4 18V20H20V18C20 15.34 14.67 14 12 14Z" fill="currentColor"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <!-- Heart icon overlay -->
                                <div class="vsp-heart-icon">
                                    <i class="fa-solid fa-heart"></i>
                                </div>
                            </div>

                            <!-- Content Side -->
                            <div class="vsp-card-content">
                                <div class="vsp-badge">
                                    <span class="vsp-badge-star"><i class="fa-solid fa-star"></i></span>
                                    <span>Volunteer Spotlight</span>
                                </div>

                                <h3 class="vsp-name"><?php echo esc_html($name); ?></h3>

                                <?php if ($designation) : ?>
                                    <p class="vsp-designation"><?php echo esc_html($designation); ?></p>
                                <?php endif; ?>

                                <?php if ($description) : ?>
                                    <div class="vsp-description">
                                        <?php echo wp_kses_post($description); ?>
                                    </div>
                                <?php endif; ?>

                                <?php
                                // Social icons — only shown if at least one account is set
                                $social_facebook  = get_post_meta(get_the_ID(), '_vsp_social_facebook',  true);
                                $social_instagram = get_post_meta(get_the_ID(), '_vsp_social_instagram', true);
                                $social_twitter   = get_post_meta(get_the_ID(), '_vsp_social_twitter',   true);
                                $social_linkedin  = get_post_meta(get_the_ID(), '_vsp_social_linkedin',  true);
                                $social_youtube   = get_post_meta(get_the_ID(), '_vsp_social_youtube',   true);
                                $has_social = ($social_facebook || $social_instagram || $social_twitter || $social_linkedin || $social_youtube);
                                if ($has_social) : ?>
                                <div class="vsp-social-links">
                                    <?php if ($social_facebook)  : ?><a href="<?php echo esc_url($social_facebook);  ?>" target="_blank" rel="noopener noreferrer" class="vsp-social-icon vsp-social-facebook"  aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a><?php endif; ?>
                                    <?php if ($social_instagram) : ?><a href="<?php echo esc_url($social_instagram); ?>" target="_blank" rel="noopener noreferrer" class="vsp-social-icon vsp-social-instagram" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a><?php endif; ?>
                                    <?php if ($social_twitter)   : ?><a href="<?php echo esc_url($social_twitter);   ?>" target="_blank" rel="noopener noreferrer" class="vsp-social-icon vsp-social-twitter"   aria-label="X / Twitter"><i class="fa-brands fa-x-twitter"></i></a><?php endif; ?>
                                    <?php if ($social_linkedin)  : ?><a href="<?php echo esc_url($social_linkedin);  ?>" target="_blank" rel="noopener noreferrer" class="vsp-social-icon vsp-social-linkedin"  aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a><?php endif; ?>
                                    <?php if ($social_youtube)   : ?><a href="<?php echo esc_url($social_youtube);   ?>" target="_blank" rel="noopener noreferrer" class="vsp-social-icon vsp-social-youtube"   aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a><?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <!-- Navigation & Pagination -->
            <div class="vsp-nav-container">
                <div class="swiper-button-prev vsp-nav-prev"></div>
                <div class="swiper-pagination vsp-pagination"></div>
                <div class="swiper-button-next vsp-nav-next"></div>
            </div>
        </div>
    </div>
    <?php
    // Hardcoded inline JS — zero file-path dependency
    ?>
    <script type="text/javascript">
        window.vspSettings = <?php echo json_encode($settings); ?>;
        <?php echo vsp_get_inline_js(); ?>
    </script>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('volunteer_spotlight', 'vsp_shortcode');

/**
 * ========================================
 * 8. FLUSH REWRITE RULES ON ACTIVATION
 * ========================================
 */
function vsp_activate() {
    vsp_register_post_type();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'vsp_activate');

function vsp_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'vsp_deactivate');

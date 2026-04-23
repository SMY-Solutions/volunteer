<?php
/**
 * Plugin Name: Volunteer Spotlight
 * Plugin URI: https://smysolutions.us/
 * Description: Showcase your amazing volunteers with beautiful spotlight cards displayed in a slider. Use shortcode [volunteer_spotlight] to display.
 * Version: 1.0.0
 * Author: Muhammad Mehdi
 * License: GPL v2 or later
 * Text Domain: volunteer-spotlight
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('VSP_VERSION', '1.0.0');
define('VSP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VSP_PLUGIN_URL', plugin_dir_url(__FILE__));

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

        <div class="vsp-field-group" style="margin-top: 20px; padding: 15px; background: #f0f0f1; border-radius: 8px;">
            <p><strong>📸 Photo:</strong> Use the <em>"Set featured image"</em> option in the right sidebar to upload the volunteer's photo.</p>
            <p><strong>📝 Name:</strong> Use the <em>"Title"</em> field above for the volunteer's name.</p>
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
    // Swiper CSS
    wp_register_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');
    // Plugin CSS
    wp_register_style('vsp-style', VSP_PLUGIN_URL . 'public/css/spotlight-style.css', array('swiper-css'), VSP_VERSION);
    // Swiper JS
    wp_register_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
    // Plugin JS
    wp_register_script('vsp-script', VSP_PLUGIN_URL . 'public/js/spotlight-script.js', array('swiper-js'), VSP_VERSION, true);
}
add_action('wp_enqueue_scripts', 'vsp_frontend_assets');

/**
 * ========================================
 * 7. SHORTCODE [volunteer_spotlight]
 * ========================================
 */
function vsp_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit'    => -1,
        'autoplay' => 'true',
        'speed'    => 4000,
        'order'    => 'DESC',
    ), $atts, 'volunteer_spotlight');

    // Enqueue assets only when shortcode is used
    wp_enqueue_style('swiper-css');
    wp_enqueue_style('vsp-style');
    wp_enqueue_script('swiper-js');
    wp_enqueue_script('vsp-script');

    // Pass settings to JS
    wp_localize_script('vsp-script', 'vspSettings', array(
        'autoplay' => $atts['autoplay'] === 'true',
        'speed'    => intval($atts['speed']),
    ));

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
                                    <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Content Side -->
                            <div class="vsp-card-content">
                                <div class="vsp-badge">
                                    <span class="vsp-badge-star">★</span>
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

                                <a href="#" class="vsp-read-more" onclick="return false;">Read More</a>
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

<?php
/**
 * Volunteer Spotlight - Uninstall
 *
 * Fired when the plugin is deleted from WordPress.
 * Cleans up all plugin data from the database.
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete all volunteer spotlight posts
$spotlights = get_posts(array(
    'post_type'      => 'volunteer_spotlight',
    'posts_per_page' => -1,
    'post_status'    => 'any',
    'fields'         => 'ids',
));

if (!empty($spotlights)) {
    foreach ($spotlights as $post_id) {
        // Delete post meta
        delete_post_meta($post_id, '_vsp_designation');
        delete_post_meta($post_id, '_vsp_description');
        // Delete the post (and its thumbnail attachment)
        wp_delete_post($post_id, true);
    }
}

// Flush rewrite rules
flush_rewrite_rules();

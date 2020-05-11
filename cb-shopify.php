<?php
/**
 * Plugin Name: WP Sync Shopify Products
 * Description: Create posts from Shopify products
 * Version: 0.0.1
 * Author: Caleb Barnes
 * Author URI: https://github.com/CalebBarnes
 */


require_once(plugin_dir_path( __FILE__ ) . 'includes/functions/console-log.php');
require_once(plugin_dir_path( __FILE__ ) . 'includes/functions/query.php');
require_once(plugin_dir_path( __FILE__ ) . 'includes/functions/fetch-products.php');
require_once(plugin_dir_path( __FILE__ ) . 'includes/functions/insert-attachment-from-url.php');

require_once(plugin_dir_path( __FILE__ ) . 'includes/acf-settings-page.php');

require_once(plugin_dir_path( __FILE__ ) . 'includes/enqueue.php');

require_once(plugin_dir_path( __FILE__ ) . 'includes/ajax-hook-sync-products.php');


// Register settings link on plugin
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'plugin_action_links_callback');
function plugin_action_links_callback( $links ) {
    $links[] = '<a href="' .
        admin_url( 'options-general.php?page=acf-options-shopify-auth-settings' ) .
        '">' . __('Settings') . '</a>';
    return $links;
}

<?php

/**
 * Plugin Name: WP Sync Shopify Products
 * Description: Create posts from Shopify products
 * Version: 0.0.3
 * Author: Caleb Barnes
 * Author URI: https://github.com/CalebBarnes
 */

require_once(plugin_dir_path(__FILE__) . 'includes/functions/insert-attachment-from-url.php');
require_once(plugin_dir_path(__FILE__) . 'includes/functions/console-log.php');
require_once(plugin_dir_path(__FILE__) . 'includes/functions/query.php');
require_once(plugin_dir_path(__FILE__) . 'includes/functions/fetch-products.php');
require_once(plugin_dir_path(__FILE__) . 'includes/functions/sync-shopify-products.php');
require_once(plugin_dir_path(__FILE__) . 'includes/functions/attach-new-product-images.php');
require_once(plugin_dir_path(__FILE__) . 'includes/functions/cleanup-stale-products.php');

require_once(plugin_dir_path(__FILE__) . 'includes/acf-settings-page.php');
require_once(plugin_dir_path(__FILE__) . 'includes/enqueue.php');


// Ajax action triggered by Force Sync in Settings page
add_action("wp_ajax_sync_shopify_products", "sync_shopify_products");

// Rest endpoint triggered by shopify webhooks - on products CRUD
add_action('rest_api_init', function () {
  register_rest_route('wordpress-shopify/v1', '/sync-products', array(
    'methods'  => 'GET',
    'callback' => 'sync_shopify_products',
  ));
});


// Register settings link on plugin
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'plugin_action_links_callback');
function plugin_action_links_callback($links)
{
  $links[] = '<a href="' .
    admin_url('options-general.php?page=wordpress-shopify-settings') .
    '">' . __('Settings') . '</a>';
  return $links;
}

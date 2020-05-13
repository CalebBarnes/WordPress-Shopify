<?php

function cleanup_stale_products($fresh_shopify_ids) {
    $wp_products = get_posts([
        'numberposts'	=> -1,
        'post_type'		=> 'product'
    ]);

    $stale_wp_product_ids = [];
        
    foreach ($wp_products as $wp_product) {
        $shopify_id = get_field('shopify_id', $wp_product->ID);
        if (!in_array($shopify_id, $fresh_shopify_ids)) {
            // post exists in WP but not in shopify!
            array_push($stale_wp_product_ids, $wp_product->ID);

            // $old_images = get_field('shopify_images', $wp_product->ID);

            // array_map(function($old_image_id){
            //     wp_delete_attachment($old_image_id);
            // }, $old_images);

            wp_trash_post($wp_product->ID);
        }
    }

    return $stale_wp_product_ids;

}

?>
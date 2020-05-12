<?php

function sync_shopify_products() {

    $shopify_response = cb_fetch_products();
    $shopify_products = json_decode(wp_remote_retrieve_body($shopify_response))->data->products->edges;

    $result['products'] = $shopify_products;
    
    $shopify_ids = []; 
    foreach ($shopify_products as $product) {
        $product = $product->node;

        array_push($shopify_ids, $product->id); // store shopify id 

        // search posts to prevent creating duplicate posts of same product
        $search = get_posts(array(
            'numberposts'	=> -1,
            'post_type'		=> 'product',
            'meta_key'		=> 'shopify_id',
            'meta_value'	=> $product->id
        ));
       
        $post_id;

        if ($search[0] !== null) {
            //* if product exists in WP already
            $post_id = $search[0]->ID;

        }

        $product_post = [
            'post_title' => $product->title,
            'post_type' => 'product',
            'post_name' => $product->handle,
            'post_status' => 'publish',
            'post_date' => $product->createdAt,
        ];

        if (isset($post_id)) {
            $product_post['ID'] = $post_id;
        }

        $post_id = wp_insert_post($product_post);

        //* add shopify data to acf fields
        update_field('shopify_id', $product->id, $post_id);
        update_field('shopify_description', $product->description, $post_id);
        update_field('shopify_available_for_sale', $product->availableForSale, $post_id);
        update_field('shopify_online_store_url', $product->onlineStoreUrl, $post_id);
        update_field('shopify_product_type', $product->productType, $post_id);

        attach_new_product_images($product->images->edges, $post_id);

    }

    $stale_products = cleanup_stale_products($shopify_ids);

    $wp_products = get_posts([
        'numberposts'	=> -1,
        'post_type'		=> 'product'
    ]);

    $result['wp_products'] = $wp_products;
    $result['shopify_ids'] = $shopify_ids;
    $result['stale_products'] = $stale_products;
    $result['type'] = 'success';

    echo json_encode($result);
    die();
}

?>
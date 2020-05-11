<?php

add_action("wp_ajax_shopify_products_sync", "shopify_products_sync");

function shopify_products_sync() {
    // if ( !wp_verify_nonce( $_REQUEST['nonce'], "shopify_products_sync_nonce")) {
    //     exit("No naughty business please");
    // }

    $result['type'] = 'success';
    $result['message'] = 'check console for products';

    $shopify_response = cb_fetch_products();

    $body = wp_remote_retrieve_body($shopify_response);

    $shopify_products = json_decode($body)->data->products->edges;
    
    $result['products'] = $shopify_products;


    foreach ($shopify_products as $product) {
        $product = $product->node;

        //? search existing posts for matching shopify_id
        //? to prevent creating duplicate posts of same product
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


        $images = $product->images->edges; 

        $result['images'] = $images;

        $attachment_ids = [];

        // TODO: ONLY DOWNLOAD IMAGES THAT DONT EXIST IN WP YET
        foreach ($images as $image) {
            $image_url = strtok($image->node->transformedSrc, '?');

            $attachment_id = crb_insert_attachment_from_url($image_url);
            array_push($attachment_ids, $attachment_id);
        }

        $result['attachment_ids'] = $attachment_ids;
        update_field('shopify_images', $attachment_ids, $post_id);

        
    }


    echo json_encode($result);
    die();
}


?>
<?php

function sync_shopify_products() {

    $shopify_response = cb_fetch_products();
    $shopify_payment_settings = json_decode(wp_remote_retrieve_body($shopify_response))->data->shop->paymentSettings;


    $enabled_currencies = [];
    // check enabled currencies
    foreach ($shopify_payment_settings->enabledPresentmentCurrencies as $enabled_currency ) {
        array_push($enabled_currencies, ['currency_code' => $enabled_currency]);
    }

    update_field('enabled_currencies', $enabled_currencies, 'option');

    $shopify_products = json_decode(wp_remote_retrieve_body($shopify_response))->data->products->edges;

    $result['products'] = $shopify_products;
    
    $new_downloaded_images = [];
    $shopify_ids = []; 
    
    $count = sprintf('%04d', 1); 
    foreach ($shopify_products as $product) {
      
        $product = $product->node;

        array_push($shopify_ids, $product->id); // store shopify id 

        $product_post = [
            'post_title' => $product->title,
            'post_type' => 'product',
            'post_name' => $product->handle,
            'post_status' => 'publish',
            'post_date' => $product->createdAt,
        ];

        // search posts to prevent creating duplicate posts of same product
        $search = get_posts(array(
            'numberposts'	=> -1,
            'post_type'		=> 'product',
            'meta_key'		=> 'shopify_id',
            'meta_value'	=> $product->id
        ));
       
         if ($search[0] !== null) {
             //* if product exists in WP already
             $product_post['ID'] = $search[0]->ID;
             $product_post['post_category'] = $search[0]->post_category;
         }

        $post_id = wp_insert_post($product_post);

        //* add shopify data to acf fields
        update_field('shopify_id', $product->id, $post_id);
        update_field('best_selling_sort_priority', $count, $post_id);
        update_field('shopify_description', $product->descriptionHtml, $post_id);
        update_field('shopify_available_for_sale', $product->availableForSale, $post_id);
        update_field('shopify_online_store_url', $product->onlineStoreUrl, $post_id);
        update_field('shopify_product_type', $product->productType, $post_id);

        $variants = [];

        foreach ($product->variants->edges as $variant) {
            $variant = $variant->node;

            $presentment_prices = [];

            foreach ($variant->presentmentPrices->edges as $presentment_price ) {
                $presentment_price = $presentment_price->node;

                array_push($presentment_prices, array(
                    'price' => $presentment_price->price->amount,
                    'compare_at_price' => $presentment_price->compareAtPrice->amount,
                    'currency_code' => $presentment_price->price->currencyCode,
                ));
            }

            
            array_push($variants, array(
                'id' => $variant->id,
                'title' => $variant->title,
                'price' => $variant->priceV2->amount,
                'currency_code' => $variant->priceV2->currencyCode,
                'compare_at_price' => $variant->compareAtPriceV2->amount,
                'available_for_sale' => $variant->availableForSale,
                'presentment_prices' => $presentment_prices,
            ));

        }

        update_field('field_5ebdf27de294d', $variants, $post_id);


        $presentment_price_ranges = [];

        foreach ($product->presentmentPriceRanges->edges as $presentmentPriceRange) {
            $presentmentPriceRange = $presentmentPriceRange->node;

            array_push($presentment_price_ranges, array(
                'max_variant_price' => $presentmentPriceRange->maxVariantPrice->amount,
                'min_variant_price' => $presentmentPriceRange->minVariantPrice->amount,
                'currency_code' => $presentmentPriceRange->maxVariantPrice->currencyCode,
            ));
        }

        update_field('presentment_price_ranges', $presentment_price_ranges, $post_id);

        // $new_images = attach_new_product_images($product->images->edges, $post_id);
        // if ($new_images) {
        //     array_push($new_downloaded_images, $new_images);
        // }
         $count = sprintf('%04d', $count + 1); 
    }

    $stale_products = cleanup_stale_products($shopify_ids);

    $wp_products = get_posts([
        'numberposts'	=> -1,
        'post_type'		=> 'product'
    ]);

    $result['wp_products'] = $wp_products;
    $result['stale_products'] = $stale_products;
    $result['shopify_products'] = $shopify_products; 
    $result['new_images'] = $new_downloaded_images;
    $result['type'] = 'success';

    echo json_encode($result);
    die();
}

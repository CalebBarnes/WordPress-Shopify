<?php
    function cb_query($query = '', $headers = '') {

        $site_url = get_field('shopify_site_url', 'option');
        $storefront_access_token = get_field('shopify_storefront_access_token', 'option');

        $endpoint = cb_sanitize_endpoint_url($site_url) . 'api/graphql';

        $request = wp_remote_post( $endpoint, [
        'headers' => [
        'Content-Type' => 'application/json',
        "X-Shopify-Storefront-Access-Token" => $storefront_access_token,
        ],
        'body' => wp_json_encode([
            'query' => $query
        ])
    ]);

    return $request;
    } 

    function cb_sanitize_endpoint_url($url = "") {
        $sanitized_url;
        if (isset($url) && strpos($url, 'http') !== false) {
            $sanitized_url = $url;
        } else if(isset($url) && strpos($url, 'http') == false) {
            $sanitized_url = 'https://' . $url;
        }

        $url_length = strlen($url);
        $url_last_char = substr($url, -$url_length);

        if ($url_last_char !== '/') {
            $sanitized_url .= '/';
        }

        return $sanitized_url;
    }
?>
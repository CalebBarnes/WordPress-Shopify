<?php
    function cb_fetch_products() {
        $storefront_access_token = get_field('shopify_storefront_access_token', 'option');
        
        $request = cb_query('{
                products(first: 250) {
                    edges {
                        node {
                        title
                        description
                        descriptionHtml
                        createdAt
                        availableForSale
                        handle
                        id
                        onlineStoreUrl
                        productType
                        tags
                        priceRange {
                            minVariantPrice {
                            amount
                            currencyCode
                            }
                            maxVariantPrice {
                            amount
                            currencyCode
                            }
                        }
                        images(first: 100) {
                            edges {
                                node {
                                    transformedSrc
                                }
                            }
                        }
                        }
                    }
                    }
            }');

            return $request;
    }   
?>
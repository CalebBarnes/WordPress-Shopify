<?php
    function cb_fetch_products() {
        $storefront_access_token = get_field('shopify_storefront_access_token', 'option');

        $request = cb_query('{
              shop {
                    paymentSettings {
                        currencyCode
                        enabledPresentmentCurrencies
                    }
                }
                products(first: 250, sortKey: BEST_SELLING) {
                    edges {
                        node {
                            presentmentPriceRanges(first:250) {
                                edges {
                                    node {
                                        maxVariantPrice {
                                            amount
                                            currencyCode
                                        }
                                        minVariantPrice {
                                            amount
                                            currencyCode
                                        }
                                    }
                                }
                            }
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
                        images(first: 250) {
                            edges {
                                node {
                                    transformedSrc
                                }
                            }
                        }
                        variants(first:250){
                            edges {
                                node {
                                title
                                id
                                compareAtPriceV2 {
                                        amount
                                        currencyCode
                                    }
                                availableForSale
                                priceV2 {
                                    amount
                                    currencyCode
                                }
                                presentmentPrices(first:250) {
                                    edges {
                                        node {
                                            price {
                                                amount
                                                currencyCode
                                            }
                                            compareAtPrice {
                                                amount
                                                currencyCode
                                            }
                                        }
                                    }
                                }
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
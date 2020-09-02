<?php

if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => "Shopify",
        'menu_title' => "Shopify",
        'menu_slug'  => "wordpress-shopify-settings",
        'icon_url'  => get_site_url() . '/wp-content/plugins/WordPress-Shopify-master/includes/assets/shopify_glyph.svg',
        'show_in_graphql' => true,
    ]);

    if (
        function_exists('acf_add_local_field_group')
    ) :

        acf_add_local_field_group(array(
            'key' => 'group_5eb73386080e0',
            'title' => 'Shopify Auth Settings',
            'fields' => array(
                array(
                    'key' => 'field_5ebe3e957dded',
                    'label' => 'Enabled Currencies',
                    'name' => 'enabled_currencies',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'show_in_graphql' => 1,
                    'collapsed' => '',
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'table',
                    'button_label' => '',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_5ebe3eb17ddee',
                            'label' => 'Currency Code',
                            'name' => 'currency_code',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'show_in_graphql' => 1,
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_5eb73453500a8',
                    'label' => 'Storefront access token',
                    'name' => 'shopify_storefront_access_token',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5eb73efbb9fcc',
                    'label' => 'Shopify Site Url',
                    'name' => 'shopify_site_url',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'sitename.myshopify.com',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_5eb75086284e6',
                    'label' => 'Force Sync Products with Shopify (be careful!)',
                    'name' => '',
                    'type' => 'message',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '<button type="button" class="button-primary" id="trigger-shopify-products-sync" data-action="shopify-products-sync">Sync Products</button>',
                    'new_lines' => 'wpautop',
                    'esc_html' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'wordpress-shopify-settings',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_graphql' => 1,
            'graphql_field_name' => 'shopifySettings',
        ));

    endif;
}

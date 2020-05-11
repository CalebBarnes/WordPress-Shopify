<?php

function cb_shopify_enqueue_scripts()
{   
    wp_register_script( "sync-button-listener", plugins_url('js/sync-button-listener.js', __FILE__), array('jquery') );
    wp_localize_script( 'sync-button-listener', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        

    wp_enqueue_script("sync-button-listener");

}

add_action('admin_enqueue_scripts', 'cb_shopify_enqueue_scripts');

?>
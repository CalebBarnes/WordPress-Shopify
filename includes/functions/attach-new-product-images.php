<?php

function attach_new_product_images($images, $post_id) {

    $attachment_ids = [];

    // TODO: ONLY DOWNLOAD IMAGES THAT DONT EXIST IN WP YET
    foreach ($images as $image) {
  
        $image_url = strtok($image->node->transformedSrc, '?'); // remove get params from url
        
        $new_filename = basename($image_url);
        

        $attachment_id = cb_insert_attachment_from_url($image_url); // download image, add to media library
        array_push($attachment_ids, $attachment_id);  // collect attachment ids in array
    }

    update_field('shopify_images', $attachment_ids, $post_id); // add new images to acf gallery field

    return $attachment_ids;
}

?>
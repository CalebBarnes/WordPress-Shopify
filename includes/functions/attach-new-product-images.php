<?php

function attach_new_product_images($images, $post_id) {

    $existing_images = get_field('shopify_images', $post_id);
    $existing_image_ids = [];
    $existing_image_names = [];

    foreach ($existing_images as $existing_img) {
        array_push($existing_image_names, $existing_img["title"]);
        array_push($existing_image_ids, $existing_img["ID"]);
    }

    $new_attachment_ids = [];
    foreach ($images as $image) {
        $image_url = strtok($image->node->transformedSrc, '?'); // remove get params from url
        $new_filename = basename($image_url);

        $name_without_extension = pathinfo($new_filename, PATHINFO_FILENAME);

        if (!in_array($name_without_extension, $existing_image_names)) {
            $attachment_id = cb_insert_attachment_from_url($image_url); // download image, add to media library

            wp_update_post(['ID' => $attachment_id, 'post_parent' => $post_id]); // attach image to product post

            array_push($new_attachment_ids, $attachment_id);  // collect attachment ids in array
        }
      
    }

    $merged_image_ids = array_merge($existing_image_ids, $new_attachment_ids);

    update_field('shopify_images', $merged_image_ids, $post_id); // add new images to acf gallery field

    return $new_attachment_ids;
}

?>
<?php
function wpc_enabled( $product_id ) {
    global $sitepress;
    if($sitepress && method_exists($sitepress, 'get_original_element_id')) {
        $product_id = $sitepress->get_original_element_id($product_id, 'post_product');
    }
    return get_post_meta( $product_id, '_wpc_check', true ) == 'yes' && get_post_type($product_id) == 'product';
}
function wpc_convert_string_value_to_int($value) {

    if($value == 'yes') { return 1; }
    else if($value == 'no') { return 0; }
    else { return $value; }

}
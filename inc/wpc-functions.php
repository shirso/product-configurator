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
function clean($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

    return  strtolower(preg_replace('/-+/', '-', $string)); // Replaces multiple hyphens with single one.
}
function get_variation_attribute_type($name){
    global $wpdb;
    $attribute_name = substr($name, 3);
    $attribute = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$attribute_name'");
    return  $attribute->attribute_type;
}
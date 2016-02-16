<?php
if (!defined('ABSPATH')) exit;
$wpc_cord_layers=get_post_meta($postId,'_wpc_cord_layers',true);
$color_dependency=get_post_meta($postId, '_wpc_color_dependency', true);
$color_base_dependency= get_post_meta($postId, '_wpc_base_color_dependency', true);
$attributes = maybe_unserialize(get_post_meta($postId, '_product_attributes', true));
$wpc_multicolor_cords=get_post_meta($postId,'_wpc_multicolor_cords',true);
?>
<h2><?=__('Choose Multicolor Cord Terms','wpc');?></h2>
<form id="wpc_multicolor_cords_form">
<select id="wpc_multicolor_cords_select" multiple name="wpc_multicolor_cords[]">
<?php  if (isset($attributes) && !empty($attributes)){ foreach ($attributes as $attr) {
    if (!$attr['is_variation']  ||  $attr['name']==$color_base_dependency ||  $attr['name']==$color_dependency) {
        continue;
    }
    ?>
    <optgroup label="<?= esc_html(wc_attribute_label($attr['name'])) ?>">
        <?php
        $cords = get_terms($attr['name']);
        if(is_array($cords) && !empty($cords)){
            foreach ($cords as $variation) {
                if (has_term(absint($variation1->term_id), $attr['name'], $postId)) {
                    $checking_value=$variation->taxonomy.'|'.$variation->term_id;
                    $selected=!empty($wpc_multicolor_cords) && in_array($checking_value,$wpc_multicolor_cords) ?"selected" : "";
        ?>
                    <option <?=$selected;?> value="<?=$variation->taxonomy?>|<?=$variation->term_id;?>"><?=$variation->name;?></option>
        <?php }}}?>
     </optgroup>
<?php }}?>
</select>
 </form>

<?php
if (!defined('ABSPATH')) exit;
$wpc_cord_layers=get_post_meta($postId,'_wpc_cord_layers',true);
?>
<h2><?=__('Choose Multicolor Cord Terms','wpc');?></h2>
<form id="wpc_multicolor_cords_form">
<select id="wpc_multicolor_cords_select" multiple name="wpc_multicolor_cords[]">
<?php if(is_array($wpc_cord_layers) && !empty($wpc_cord_layers)){
 foreach($wpc_cord_layers as $layer){
     if (!$layer['is_variation']) {
         continue;
     }
     ?>
     <optgroup label="<?= esc_html(wc_attribute_label($layer)) ?>">
         <?php
         $cords = get_terms($layer);
            if(is_array($cords) && !empty($cords)){
                foreach ($cords as $variation) {
                    if (has_term(absint($variation1->term_id), $layer, $postId)) {
                    ?>
                   <option value="<?=$variation->term_id;?>"><?=$variation->name;?></option>
                   <?php }
                }}
         ?>

     </optgroup>

<?php  }} ?>
</select>
 </form>

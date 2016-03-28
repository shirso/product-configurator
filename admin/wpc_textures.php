<?php
if (!defined('ABSPATH')) exit;
$settings=get_option('wpc_settings');
$allTextures=$settings["texture_data"];
$wpc_cord_layers=get_post_meta($postId,'_wpc_cord_layers',true);
$wpc_no_cords=get_post_meta($postId,'_wpc_no_cords',true);
$edgeLayer=get_post_meta($postId,'_wpc_edge_layer',true);
if(is_array($wpc_cord_layers) && !empty($wpc_cord_layers)){?>
    <form id="wpc_textures_form">
    <?php foreach($wpc_cord_layers as $k=>$layer){
        $all_terms= $post_terms = wp_get_post_terms($postId, $layer);
        $no_cord=($wpc_no_cords[$layer])?$wpc_no_cords[$layer]:array();
        ?>
        <h2><?php _e('Multi Color For','wpc')?> <?=wc_attribute_label($layer);?></h2>
     <?php } ?>
    </form>
<?php }

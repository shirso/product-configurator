<?php
if (!defined('ABSPATH')) exit;
$wpc_multicolor_cords=get_post_meta($postId,'_wpc_multicolor_cords',true);
$wpc_cord_layers=get_post_meta($postId,'_wpc_cord_layers',true);
$wpc_textures_images=get_post_meta($postId,'_wpc_textures_images',true);
 print_r($wpc_cord_layers);
?>
<form id="wpc_multicolor_images_form">
<?php if(isset($wpc_multicolor_cords) && !empty($wpc_multicolor_cords)){
foreach($wpc_multicolor_cords as $cords){
    $split_data=explode('|',$cords);
    $taxonomy=$split_data[0];
    $term_id=$split_data[1];
    $term_data=get_term_by('id',$term_id,$taxonomy);
    ?>
    <h2><?= esc_html(wc_attribute_label($taxonomy));?> (<?=$term_data->name;?>) <?=__('Combinations','wpc')?></h2>
    <div class="wpc_combinations_texture11" data-layer="<?=$term_id;?>" id="wpc_textures_<?=$term_id?>">
        <div id="wpc_textures_<?=$term_id?>_template">
            <div class="wpc_combination_wrapper">
                <div class="wc-metabox">
                    <?php if(isset($wpc_cord_layers) && !empty($wpc_cord_layers)){ foreach($wpc_cord_layers as $l){ ?>
                    <?php if($l!=$taxonomy){?>
                            <label for="wpc_textures_<?=$term_id?>_#index#_<?=$l?>"><?=esc_html(wc_attribute_label($l));?></label>
                        <select name="wpc_textures[<?=$term_id;?>][#index#][<?=$l;?>]" id="wpc_textures_<?=$term_id?>_#index#_<?=$l?>">
                        </select>
                        <?php }}}?>
                </div>
            </div>
        </div>
        <div id="wpc_textures_<?=$term_id?>_#index#_images_noforms_template"><?=__('No Image','wpc');?></div>
        <div id="wpc_textures_<?=$term_id?>_#index#_images_controls" class="row btln">
            <div id="wpc_textures_<?=$term_id?>_#index#_images_add" ><a class="button button-default"><span><?=__('Add Image','wpc')?></span></a></div>
            <div id="wpc_textures_<?=$term_id?>_#index#_images_remove_last" ><a class="button button-default"><span><?=__('Remove Image','wpc')?></span></a></div>
        </div>
    </div>
<?php }}?>
</form>

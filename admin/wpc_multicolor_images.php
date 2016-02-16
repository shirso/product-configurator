<?php
if (!defined('ABSPATH')) exit;
$wpc_multicolor_cords=get_post_meta($postId,'_wpc_multicolor_cords',true);
$wpc_cord_layers=get_post_meta($postId,'_wpc_cord_layers',true);
?>
<form id="wpc_multicolor_cords_form">
<?php if(isset($wpc_multicolor_cords) && !empty($wpc_multicolor_cords)){
 foreach($wpc_multicolor_cords as $cords){
     $split_data=explode('|',$cords);
     $taxonomy=$split_data[0];
     $term_id=$split_data[1];
     $term_data=get_term_by('id',$term_id,$taxonomy);
     ?>
     <h2><?= esc_html(wc_attribute_label($taxonomy));?> (<?=$term_data->name;?>) <?=__('Combinations','wpc')?></h2>
     <div class="wpc_combinations_texture" data-layer="<?=$term_id;?>" id="wpc_textures_<?=$term_id?>">
         <div id="wpc_textures_<?=$term_id?>_template">
             <div class="wpc_combination_wrapper">
                 <div class="wc-metabox">
                     <?php if(isset($wpc_cord_layers) && !empty($wpc_cord_layers)){ foreach($wpc_cord_layers as $l){ ?>
                         <?php if($l!=$taxonomy){?>
                             <label for="wpc_textures_<?=$term_id?>_#index#_<?=$l?>"><?=esc_html(wc_attribute_label($l));?></label>
                             <select name="wpc_textures[<?=$term_id;?>][#index#][<?=$l;?>]" id="wpc_textures_<?=$term_id?>_#index#_<?=$l?>">
                                 <option value="">---</option>
                                 <?php $cords = get_terms($l);
                                        if(is_array($cords) && !empty($cords)){
                                            foreach ($cords as $variation) {
                                                if (has_term(absint($variation->term_id), $l, $postId)){
                                                    ?>
                                                    <option value="<?=$variation->term_id;?>"><?=$variation->name;?></option>
                                               <?php }
                                            }
                                        }
                                 ?>
                             </select>
                       <?php }}}?>
                 </div>
                 <div class="wpc_textures">
                     <div id="wpc_textures_<?=$term_id?>_#index#_images">
                         <div id="wpc_textures_<?=$term_id?>_#index#_images_template" class="">
                             Here Goes
                          </div>
                         <div id="wpc_textures_<?=$term_id?>_#index#_images_noforms_template"><?=__('No Images','wbm');?></div>

                      </div>
                 </div>
              </div>
         </div>
         <div id="wpc_textures_<?=$term_id?>_noforms_template"><?php _e('No Combination','wpc'); ?></div>
         <div id="wpc_textures_<?=$term_id?>_controls" class="row">
             <div id="wpc_textures_<?=$term_id?>_add" class="col-sm-3"><a class="button button-primary"><span><?php _e('Add Combination','wpc'); ?></span></a></div>
             <div id="wpc_textures_<?=$term_id?>_remove_last" class="col-sm-3"><a class="button button-primary"><span><?php _e('Remove','wpc'); ?> </span></a></div>
             <div id="wpc_textures_<?=$term_id?>_remove_all"><a><span><?php _e('Remove all','wpc'); ?></span></a></div>
             <div id="wpc_textures_<?=$term_id?>_add_n" class="col-sm-6">
                 <div class="row">
                     <div class="col-sm-2"><input id="wpc_textures_<?=$term_id?>_add_n_input" type="text" size="4" /></div>
                     <div class="col-sm-10" id="wpc_textures_<?=$term_id?>_add_n_button"><a class="button button-primary"><span><?php _e('Add','wpc'); ?> </span></a></div></div>
             </div>
         </div>
     </div>
<?php }} ?>
</form>

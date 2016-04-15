<?php
if (!defined('ABSPATH')) exit;
$wpc_cord_layers=get_post_meta($postId,'_wpc_cord_layers',true);
$wpc_multicolor_cords=get_post_meta($postId,'_wpc_multicolor_cords',true) ? get_post_meta($postId,'_wpc_multicolor_cords',true): array();
$wpc_cord_images=get_post_meta($postId,'_wpc_cord_images_'.$termId,true);
$wpc_no_cords=get_post_meta($postId,'_wpc_no_cords',true);
?>
    <form id="wpc_cord_images_form">
     <?php $wpc_combinations=array();
     if(isset($wpc_cord_images['combinations']) && !empty($wpc_cord_images['combinations'])){
         foreach($wpc_cord_images['combinations'] as $key=>$c){
             $cord_image=$wpc_cord_images['images'][$key];
               if(!empty($c)){
                 foreach($c as $k=>$v){
                    $c['wpc_cord_images_combinations_'.$k.'_#index#']=$v;

                    $c['wpc_cord_images_images_'.$k.'_base_#index#'] =isset($cord_image[$k]["base"])?$cord_image[$k]["base"]:"";
                    $c['wpc_cord_images_images_'.$k.'_texture_#index#'] =isset($cord_image[$k]["texture"])?$cord_image[$k]["texture"]:"";

                     unset($c[$k]);
                 }
             }
             array_push($wpc_combinations,$c);
         }
     }
     ?>
        <input type="hidden" id="wpc_combinations_data" value='<?=json_encode($wpc_combinations);?>'>
   <?php if(is_array($wpc_cord_layers) && !empty($wpc_cord_layers)){?>
    <div id="wpc_combinations" class="wpc_combinations">
        <div id="wpc_combinations_template">
            <div class="wpc_combination_wrapper">
                <div class="wc-metabox">
                <?php foreach($wpc_cord_layers as $layer){?>
                        <lable><?= esc_html(wc_attribute_label($layer));?></lable>
                        <select name="wpc_cord_images[combinations][#index#][<?=$layer?>]" id="wpc_cord_images_combinations_<?=$layer?>_#index#">
                            <option value="">---</option>
                            <?php $cords = get_terms($layer);
                                 if(is_array($cords) && !empty($cords)){
                                     foreach ($cords as $variation) {
                                         if (has_term(absint($variation->term_id), $layer, $postId)) {?>
                                             <option value="<?=$variation->slug;?>"><?=$variation->name;?></option>
                                        <?php }}} ?>
                        </select>

                 <?php }?>
                </div>
                <div class="wc-metabox">
                    <table>
                        <?php foreach($wpc_cord_layers as $layer){?>
                            <tr>
                                <td>
                                    <?= esc_html(wc_attribute_label($layer));?>
                                </td>
                                <td>
                                    <table>
                                        <tr>
                                            <th><?=__('Base Image','wpc');?></th>
                                            <th><?=__('Texture Image','wpc');?></th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="wpc_cord_images[images][#index#][<?=$layer?>][base]" id="wpc_cord_images_images_<?=$layer?>_base_#index#">
                                                <button class="button button-secondary wpc_image_upload" data-field="wpc_cord_images_images_<?=$layer?>_base_#index#"><?=__('Upload','wpc');?></button>
                                            </td>
                                            <td>
                                                <input type="text" name="wpc_cord_images[images][#index#][<?=$layer?>][texture]" id="wpc_cord_images_images_<?=$layer?>_texture_#index#">
                                                <button class="button button-secondary wpc_image_upload" data-field="wpc_cord_images_images_<?=$layer?>_texture_#index#"><?=__('Upload','wpc');?></button>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                         <?php }?>
                        <tr>
                            <td colspan="2" style="text-align: center">
                                <button class="button" id="wpc_combinations_remove_current"><?=__('Remove This','wpc')?></button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div id="wpc_combinations_noforms_template"><?php _e('No Combination','wpc'); ?></div>
        <div id="wpc_combinations_controls" class="row">
            <div id="wpc_combinations_add" class="col-sm-3"><a class="button button-primary"><span><?php _e('Add Combination','wpc'); ?></span></a></div>
            <div id="wpc_combinations_remove_all"><a class="button button-primary"><span><?php _e('Remove all','wpc'); ?></span></a></div>
            <div id="wpc_combinations_add_n" class="col-sm-6">
                <div class="row">
                    <div cla ss="col-sm-2"><input id="wpc_combinations_add_n_input" type="text" size="4" /></div>
                    <div class="col-sm-10" id="wpc_combinations_add_n_button"><a class="button button-primary"><span><?php _e('Add','wpc'); ?> </span></a></div></div>
            </div>
        </div>
     </div>
     <?php }?>
     </form>
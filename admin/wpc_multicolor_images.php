<?php
if (!defined('ABSPATH')) exit;
$wpc_cord_layers=get_post_meta($postId,'_wpc_cord_layers',true);
$allTexturesMeta=get_post_meta($postId,"_wpc_textures_".$termId,true);
$edge_layer=get_post_meta($postId,'_wpc_edge_layer',true);
$wpc_multicord_images=get_post_meta($postId,'_wpc_multicord_images_'.$termId,true);
?>
<form id="wpc_multicolor_images_form">
    <?php $wpc_combinations=array();
    if(isset($wpc_multicord_images['combinations']) && !empty($wpc_multicord_images['combinations'])){
        foreach($wpc_multicord_images['combinations'] as $key=>$c){
            $cord_image=$wpc_multicord_images['images'][$key];
            if(!empty($c)){
                foreach($c as $k=>$v){
                    $c['wpc_multicord_images_combinations_'.$k.'_#index#']=$v;
                    $textureCord=$cord_image[$k];
                   if(!empty($textureCord)){
                       foreach($textureCord as $p=>$q){
                           $c['wpc_multicord_images_images_'.$k.'_'.$p.'_#index#'] =isset($cord_image[$k][$p])?$cord_image[$k][$p]:"";
                       }
                   }
                    unset($c[$k]);
                }
           }
            array_push($wpc_combinations,$c);
        }
   }
    ?>
    <input type="hidden" id="wpc_texture_combinations_data" value='<?=json_encode($wpc_combinations);?>'>
    <?php if(is_array($wpc_cord_layers) && !empty($wpc_cord_layers)){?>
        <div id="wpc_texture_combinations" class="wpc_combinations">
            <div id="wpc_texture_combinations_template">
                <div class="wpc_combination_wrapper">
                    <div class="wc-metabox">
                        <?php foreach($wpc_cord_layers as $layer){?>
                            <lable><?= esc_html(wc_attribute_label($layer));?></lable>
                            <select name="wpc_muticord_images[combinations][#index#][<?=$layer?>]" id="wpc_multicord_images_combinations_<?=$layer?>_#index#">
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
                            <?php foreach($wpc_cord_layers as $layer){
                                if($layer!=$edge_layer){
                                ?>
                                <tr>
                                    <th><h2 style="margin-bottom: 5px"><?=esc_html(wc_attribute_label($layer));?></h2></th>
                                </tr>
                                <tr>
                                    <td>
                                        <table>
                                            <?php if(isset($allTexturesMeta[$layer]) && !empty($allTexturesMeta[$layer])){
                                                foreach($allTexturesMeta[$layer] as $key=>$textureLayer){
                                                ?>
                                                    <tr>
                                                        <?php $termDetails=get_term_by("slug",$key,$layer);?>
                                                        <th><?=$termDetails->name;?></th>
                                                    </tr>
                                                   <tr>
                                                       <td>
                                                           <table style="overflow-x: scroll;height: 100px;">
                                                               <?php if(isset($textureLayer["textures"]) && !empty($textureLayer["textures"])){ ?>
                                                              <?php foreach($textureLayer["textures"] as $t){
                                                                       $spitData=explode("|",$t);
                                                                       ?>
                                                               <tr>
                                                                    <td><?=$spitData[0]?></td>
                                                                    <td>
                                                                        <input type="text" name="wpc_muticord_images[images][#index#][<?=$layer?>][<?=clean($spitData[0]);?>]" id="wpc_multicord_images_images_<?=$layer?>_<?=clean($spitData[0]);?>_#index#">
                                                                        <button class="button button-secondary wpc_image_upload" data-field="wpc_multicord_images_images_<?=$layer?>_<?=clean($spitData[0]);?>_#index#"><?=__('Upload','wpc');?></button>
                                                                    </td>
                                                               </tr>
                                                                <?php }}?>
                                                           </table>
                                                       </td>
                                                   </tr>
                                            <?php }}?>
                                        </table>
                                    </td>
                                </tr>
                            <?php }}?>
                            <tr>
                                <td colspan="2" style="text-align: center">
                                    <button class="button" id="wpc_texture_combinations _remove_current"><?=__('Remove This','wpc')?></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
             </div>
            <div id="wpc_texture_combinations_noforms_template"><?php _e('No Combination','wpc'); ?></div>
            <div id="wpc_texture_combinations_controls" class="row">
                <div id="wpc_texture_combinations_add" class="col-sm-3"><a class="button button-primary"><span><?php _e('Add Combination','wpc'); ?></span></a></div>
                <div id="wpc_texture_combinations_remove_all"><a class="button button-primary"><span><?php _e('Remove all','wpc'); ?></span></a></div>
                <div id="wpc_texture_combinations_add_n" class="col-sm-6">
                    <div class="row">
                        <div cla ss="col-sm-2"><input id="wpc_texture_combinations_add_n_input" type="text" size="4" /></div>
                        <div class="col-sm-10" id="wpc_texture_combinations_add_n_button"><a class="button button-primary"><span><?php _e('Add','wpc'); ?> </span></a></div></div>
                </div>
             </div>
        </div>
    <?php }?>
</form>

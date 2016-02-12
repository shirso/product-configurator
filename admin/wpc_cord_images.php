<?php
if (!defined('ABSPATH')) exit;
$wpc_cord_layers=get_post_meta($postId,'_wpc_cord_layers',true);
$wpc_multicolor_cords=get_post_meta($postId,'_wpc_multicolor_cords',true) ? get_post_meta($postId,'_wpc_multicolor_cords',true): array();
$wpc_cord_images=get_post_meta($postId,'_wpc_cord_images',true);
$wpc_no_cords=get_post_meta($postId,'_wpc_no_cords',true);
if(is_array($wpc_cord_layers) && !empty($wpc_cord_layers)){?>
    <form id="wpc_cord_images_form">
    <?php foreach($wpc_cord_layers as $k=>$layer){
        $wpc_combinations=array();
        if(isset($wpc_cord_images[$layer]) && !empty($wpc_cord_images[$layer])){
            foreach($wpc_cord_images[$layer] as $l){
               foreach($l as $key=>$m){
                   $l['wpc_combination_'.$layer.'_#index#_'.$key] = $m;
                   unset($l[$key]);
               }
                array_push($wpc_combinations,$l);
            }
        }
        ?>
        <input type="hidden" id='wpc_values_<?=$layer?>' value='<?=json_encode($wpc_combinations)?>'>
        <h2><?= esc_html(wc_attribute_label($layer));?> <?=__('Combinations','wpc')?></h2>
        <div class="toolbar" style="margin-bottom: 10px">
           <label for="wpc_no_cord_<?=$layer?>"><?=__('No Cord Term','wpc')?></label>
            <select name="wpc_no_cords[<?=$layer?>]" id="wpc_no_cord_<?=$layer?>">
                <option value="">---</option>
                <?php $cords2 = get_terms($layer);
                if(is_array($cords2) && !empty($cords2)){
                    foreach ($cords2 as $variation2) {
                        $checked_term=$variation2->term_id == $wpc_no_cords[$layer] ? 'selected' :'';
                        if (has_term(absint($variation2->term_id), $layer, $postId) && !in_array($variation2->term_id,$wpc_multicolor_cords)) {
                            ?>
                            <option <?=$checked_term?> value="<?=$variation2->term_id;?>"><?=$variation2->name;?></option>
                        <?php }}} ?>
            </select>
        </div>
        <div id="wpc_combination_<?=$layer?>" data-layer="<?=$layer?>" class="wpc_combinations">
            <div id="wpc_combination_<?=$layer?>_template">
                <div class="wpc_combination_wrapper">
                <div class="wc-metabox">
                    <lable><?= esc_html(wc_attribute_label($layer));?></lable>
                    <select name="wpc_combination[<?=$layer?>][#index#][<?=$layer?>]" id="wpc_combination_<?=$layer?>_#index#_<?=$layer?>">
                        <option value="">---</option>
                        <?php $cords = get_terms($layer);
                        if(is_array($cords) && !empty($cords)){
                            foreach ($cords as $variation) {
                                if (has_term(absint($variation->term_id), $layer, $postId) && !in_array($variation->term_id,$wpc_multicolor_cords)) {
                                    ?>
                                    <option value="<?=$variation->term_id;?>"><?=$variation->name;?></option>
                                <?php }}} ?>
                    </select>
                    <?php
                    $copied_array=$wpc_cord_layers;
                    unset($copied_array[$k]);
                    if(is_array($copied_array) && !empty($copied_array)){
                        foreach ($copied_array as $layer1) {?>
                            <lable><?= esc_html(wc_attribute_label($layer1));?></lable>
                            <select name="wpc_combination[<?=$layer?>][#index#][<?=$layer1?>]" id="wpc_combination_<?=$layer?>_#index#_<?=$layer1?>">
                                <option value="">---</option>
                                <?php $cords1 = get_terms($layer1);
                                if(is_array($cords1) && !empty($cords1)){
                                    foreach ($cords1 as $variation1) {
                                        if (has_term(absint($variation1->term_id), $layer1, $postId) && !in_array($variation1->term_id,$wpc_multicolor_cords)) {
                                            ?>
                                            <option value="<?=$variation1->term_id;?>"><?=$variation1->name;?></option>
                                        <?php }}} ?>
                            </select>
                        <?php }} ?>
                </div>
                <table>
                    <tr>
                        <th><?=__('Base Image','wpc');?></th>
                        <th><?=__('Texture Image','wpc');?></th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="wpc_combination[<?=$layer?>][#index#][wpc_base_image]" id="wpc_combination_<?=$layer?>_#index#_wpc_base_image">
                            <button class="button button-secondary wpc_image_upload_sheepit"><?=__('Upload','wpc');?></button>
                        </td>
                        <td>
                            <input type="text" name="wpc_combination[<?=$layer?>][#index#][wpc_texture_image]" id="wpc_combination_<?=$layer?>_#index#_wpc_texture_image">
                            <button class="button button-secondary wpc_image_upload_sheepit"><?=__('Upload','wpc');?></button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="wpc_center">
                            <button class="button" id="wpc_combination_<?=$layer?>_remove_current"><?=__('Remove This','wpc');?></button>
                        </td>
                    </tr>
                </table>
                        <table>

                        </table>
                </div>
            </div>
            <div id="wpc_combination_<?=$layer?>_noforms_template"><?php _e('No Combination','wpc'); ?></div>
            <div id="wpc_combination_<?=$layer?>_controls" class="row">
                <div id="wpc_combination_<?=$layer?>_add" class="col-sm-3"><a class="button button-primary"><span><?php _e('Add Combination','wpc'); ?></span></a></div>
                <div id="wpc_combination_<?=$layer?>_remove_last" class="col-sm-3"><a class="button button-primary"><span><?php _e('Remove','wpc'); ?> </span></a></div>
                <div id="wpc_combination_<?=$layer?>_remove_all"><a><span><?php _e('Remove all','wpc'); ?></span></a></div>
                <div id="wpc_combination_<?=$layer?>_add_n" class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-2"><input id="wpc_combination_<?=$layer?>_add_n_input" type="text" size="4" /></div>
                        <div class="col-sm-10" id="wpc_combination_<?=$layer?>_add_n_button"><a class="button button-primary"><span><?php _e('Add','wpc'); ?> </span></a></div></div>
                </div>
            </div>
        </div>
    <?php }?></form><?php }
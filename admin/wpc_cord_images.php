<?php
if (!defined('ABSPATH')) exit;
$wpc_cord_layers=get_post_meta($postId,'_wpc_cord_layers',true);
$wpc_multicolor_cords=get_post_meta($postId,'_wpc_multicolor_cords',true) ? get_post_meta($postId,'_wpc_multicolor_cords',true): array();

if(is_array($wpc_cord_layers) && !empty($wpc_cord_layers)){
    foreach($wpc_cord_layers as $k=>$layer){
        ?>
        <h2><?= esc_html(wc_attribute_label($layer));?> <?=__('Combinations','wpc')?></h2>
        <div id="wpc_combinations_<?=$layer?>">
            <div id="wpc_combinations_<?=$layer?>_template">
                <div class="wc-metabox">
                    <lable><?= esc_html(wc_attribute_label($layer));?></lable>
                    <select>
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
                    $copied_array=$wpc_cord_layers; unset($copied_array[$k]);
                    if(is_array($copied_array) && !empty($copied_array)){
                        foreach ($copied_array as $layer1) {?>
                            <lable><?= esc_html(wc_attribute_label($layer1));?></lable>
                            <select>
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
                            <input type="text">
                        </td>
                        <td>
                            <input type="text">
                        </td>
                    </tr>

                </table>
            </div>
            <div id="wpc_combinations_<?=$layer?>_template"><?php _e('No Combination','wpc'); ?></div>
            <div id="wpc_combinations_<?=$layer?>_controls" class="row">
                <div id="wpc_combinations_<?=$layer?>_add" class="col-sm-3"><a class="button button-primary"><span><?php _e('Add Combination','wpc'); ?></span></a></div>
                <div id="wpc_combinations_<?=$layer?>_remove_last" class="col-sm-3"><a class="button button-primary"><span><?php _e('Remove','wpc'); ?> </span></a></div>
                <div id="wpc_combinations_<?=$layer?>_remove_all"><a><span><?php _e('Remove all','wpc'); ?></span></a></div>
                <div id="wpc_combinations_<?=$layer?>_add_n" class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-2"><input id="wpc_combinations_<?=$layer?>_add_n_input" type="text" size="4" /></div>
                        <div class="col-sm-10" id="wpc_combinations_<?=$layer?>_add_n_button"><a class="button button-primary"><span><?php _e('Add','wpc'); ?> </span></a></div></div>
                </div>
            </div>
        </div>
    <?php }}

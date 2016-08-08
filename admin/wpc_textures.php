<?php
if (!defined('ABSPATH')) exit;
$settings=get_option('wpc_settings');
$allTextures=$settings["texture_data"];
$wpc_multicolor_cords=get_post_meta($postId,'_wpc_multicolor_cords',true) ? get_post_meta($postId,'_wpc_multicolor_cords',true): array();
$wpc_cord_layers=get_post_meta($postId,'_wpc_cord_layers',true);
$wpc_no_cords=get_post_meta($postId,'_wpc_no_cords',true);
$edgeLayer=get_post_meta($postId,'_wpc_edge_layer',true);
$settings=get_option('wpc_settings');
$allColors=$settings["texture_data"];
$all_static_layers=get_post_meta($postId,'_wpc_static_layers',true);
$all_available_models=get_post_meta($postId,'_wpc_available_models',true);
$allTexturesMeta=get_post_meta($postId,"_wpc_textures_".$termId,true);
$all_manufacturer = get_terms( 'wpc_texture_manufacturer', array(
    'hide_empty' => true
));
$all_Colors=get_posts(array(
    'posts_per_page'=> -1,
    'post_type'=> 'wpc_textures',
    'post_status'=> 'publish',
    'orderby'=>'name',
));
?>
    <form id="wpc_textures_form">
        <table>
            <tr>
                <td><?=__("Copy Texture Data from Another Model","wpc")?></td>
                <td>
                    <select id="wpc_copy_model_select_texture">
                        <option value="">---</option>
                        <?php if(!empty($all_available_models)){ foreach($all_available_models as $k=>$model){?>
                            <?php if($model!=$termId){
                                $modelDetails=get_term_by('id',$model,$taxonomy);
                                ?>
                                <option value="<?=$model?>"><?=$modelDetails->name;?></option>
                            <?php }?>
                        <?php }}?>
                    </select>
                </td>
                <td>
                    <button type="button" data-select="#wpc_copy_model_select_texture" data-term="<?=$termId?>" class="primary button" id="wpc_model_copy_texture"><?=__("Copy","wpc");?></button>
                </td>
            </tr>
        </table>
        <?php if(!empty($all_static_layers)){foreach($all_static_layers as $m=>$static_layer){
            $all_terms= $post_terms = wp_get_post_terms($postId, $static_layer);
            $no_cord=($wpc_no_cords[$static_layer]) ? $wpc_no_cords[$static_layer]: array();
            ?>
            <h2><?php _e('Multi Color For','wpc')?> <?=wc_attribute_label($static_layer);?></h2>
            <table>
                <tr>
                    <th><?=__("Term Name","wpc");?></th>
                    <th><?=__("Multi Colors","wpc");?></th>
                </tr>
                <?php if(!empty($all_terms)){
                    foreach($all_terms as $term){
                        $checking_value=$term->taxonomy.'|'.$term->term_id;
                        if (has_term(absint($term->term_id), $static_layer, $postId) && (in_array($term->term_id,$wpc_multicolor_cords) && !in_array($term->term_id,$wpc_no_cords))) {
                            $check_color=isset($allTexturesMeta[$static_layer][$term->slug]["textures"]) ? $allTexturesMeta[$static_layer][$term->slug]["textures"]:array();
                            ?>
                            <tr>
                                <td><?=$term->name?></td>
                                <td>
                                    <table style="border-bottom: 1px solid #000000" cellspacing="10">
                                        <?php if(!empty($all_Colors)){
                                            ?>
                                            <tr><th><?=__("Manufacturers","wpc")?></th><th>&nbsp;</th><th style="text-align: center">
                                                    <select class="wpc_selection">
                                                        <option value="">---</option>
                                                        <option value="all"><?=__("All","wpc")?></option>
                                                        <option value="none"><?=__("None","wpc")?></option>
                                                        <?php if(!empty($all_manufacturer)){?>
                                                            <?php foreach ($all_manufacturer as $man){ ?>
                                                                <option value="<?=$man->term_id?>"><?=$man->name?></option>
                                                            <?php }?>
                                                        <?php }?>
                                                    </select>
                                                </th></tr>
                                            <?php
                                            foreach($all_Colors as $Color){
                                                $all_manufacturer_for_this_post= !empty(wp_get_post_terms( $Color->ID, 'wpc_texture_manufacturer',array('fields'=>'ids')))?wp_get_post_terms( $Color->ID, 'wpc_texture_manufacturer',array('fields'=>'ids')):array();
                                                ?>
                                                <tr>
                                                    <td><?=$Color->post_title;?></td>
                                                    <td style="text-align: center">
                                                        <?php $checked_color=in_array($Color->ID,$check_color)?"checked":""; ?>
                                                        <input <?=$checked_color;?> class="color_checkbox" data-manu="<?=json_encode($all_manufacturer_for_this_post);?>"  type="checkbox" value="<?=$Color->ID?>" name="wpc_textures[<?=$static_layer?>][<?=$term->slug?>][textures][]">
                                                    </td>
                                                </tr>
                                            <?php }}?>
                                    </table>
                                </td>
                            </tr>
                        <?php }}}?>
            </table>
        <?php }} ?>
    <?php if(!empty($wpc_cord_layers)){foreach($wpc_cord_layers as $k=>$layer){
        $all_terms= $post_terms = wp_get_post_terms($postId, $layer);
        $no_cord=($wpc_no_cords[$layer])?$wpc_no_cords[$layer]:array();
        ?>
        <h2><?php _e('Multi Color For','wpc')?> <?=wc_attribute_label($layer);?></h2>
        <table>
            <tr>
                <th><?=__("Term Name","wpc");?></th>
                <th><?=__("Multi Colors","wpc");?></th>
            </tr>
        <?php if(!empty($all_terms)){
            foreach($all_terms as $term){
                $checking_value=$term->taxonomy.'|'.$term->term_id;
                if (has_term(absint($term->term_id), $layer, $postId) && (in_array($term->term_id,$wpc_multicolor_cords) && !in_array($term->term_id,$wpc_no_cords))) {
                    $check_color=isset($allTexturesMeta[$layer][$term->slug]["textures"]) ? $allTexturesMeta[$layer][$term->slug]["textures"]:array();
                    ?>
            <tr>
                <td><?=$term->name?></td>
                <td>
                    <table style="border-bottom: 1px solid #000000" cellspacing="10">
                        <?php if(!empty($all_Colors)){
                            ?>
                            <tr><th><?=__("Manufacturers","wpc")?></th><th>&nbsp;</th><th style="text-align: center">
                                    <select class="wpc_selection">
                                        <option value="">---</option>
                                        <option value="all"><?=__("All","wpc")?></option>
                                        <option value="none"><?=__("None","wpc")?></option>
                                        <?php if(!empty($all_manufacturer)){?>
                                            <?php foreach ($all_manufacturer as $man){ ?>
                                                <option value="<?=$man->term_id?>"><?=$man->name?></option>
                                            <?php }?>
                                        <?php }?>
                                    </select>
                                </th></tr>
                            <?php
                            foreach($all_Colors as $Color){
                                $all_manufacturer_for_this_post= !empty(wp_get_post_terms( $Color->ID, 'wpc_texture_manufacturer',array('fields'=>'ids')))?wp_get_post_terms( $Color->ID, 'wpc_texture_manufacturer',array('fields'=>'ids')):array();
                                ?>
                                <tr>
                                    <td><?=$Color->post_title;?></td>
                                    <td style="text-align: center">
                                        <?php $checked_color=in_array($Color->ID,$check_color)?"checked":""; ?>
                                        <input <?=$checked_color;?> class="color_checkbox" data-manu="<?=json_encode($all_manufacturer_for_this_post);?>"  type="checkbox" value="<?=$Color->ID?>" name="wpc_textures[<?=$layer?>][<?=$term->slug?>][textures][]">
                                    </td>
                                </tr>
                            <?php }}?>
                    </table>
                </td>
             </tr>
       <?php }}}?>
        </table>
     <?php }} ?>
    </form>

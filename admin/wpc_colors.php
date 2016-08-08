<?php
if (!defined('ABSPATH')) exit;
$wpc_cord_layers=get_post_meta($postId,'_wpc_cord_layers',true);
$wpc_multicolor_cords=get_post_meta($postId,'_wpc_multicolor_cords',true) ? get_post_meta($postId,'_wpc_multicolor_cords',true): array();
$wpc_no_cords=get_post_meta($postId,'_wpc_no_cords',true);
$settings=get_option('wpc_settings');
$allColors=$settings["colors_data"];
$allColorsMeta=get_post_meta($postId,"_wpc_colors_".$termId,true);
$edgeLayer=get_post_meta($postId,'_wpc_edge_layer',true);
$baseLayer=get_post_meta($postId,'_wpc_base_color_dependency',true);
$all_static_layers=get_post_meta($postId,'_wpc_static_layers',true);
$all_available_models=get_post_meta($postId,'_wpc_available_models',true);
?>
<form id="wpc_colors_form">
    <table>
        <tr>
            <td><?=__("Copy Color Data from Another Model","wpc")?></td>
            <td>
                <select id="wpc_copy_model_select_color">
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
                <button type="button" data-select="#wpc_copy_model_select_color" data-term="<?=$termId?>" class="primary button" id="wpc_model_copy_color"><?=__("Copy","wpc");?></button>
            </td>
        </tr>
    </table>
    <?php if(!empty($baseLayer)){?>
        <h2><?php _e('Colors For','wpc')?> <?=wc_attribute_label($baseLayer);?></h2>
        <?php
        $all_terms= wp_get_post_terms($postId, $baseLayer);
        ?>
        <table>
            <tr>
                <th><?=__("Term Name","wpc");?></th>
                <th><?=__("Colors","wpc");?></th>
            </tr>
            <?php if(!empty($all_terms)){
                foreach($all_terms as $term){
                    $checking_value=$term->taxonomy.'|'.$term->term_id;
                    if (has_term(absint($term->term_id), $baseLayer, $postId) && !in_array($term->term_id,$wpc_multicolor_cords)) {
                        $check_color=isset($allColorsMeta[$baseLayer][$term->slug]["colors"]) ? $allColorsMeta[$baseLayer][$term->slug]["colors"]:array();
                        ?>
                        <tr>
                            <td><?=$term->name?></td>
                            <td>
                                <table style="border-bottom: 1px solid #000000" cellspacing="10">
                                    <?php if(!empty($allColors)){
                                        ?>
                                        <tr><th><?=__("Manufacturers","wpc")?></th><th>&nbsp;</th><th style="text-align: center">
                                            <select class="wpc_selection">
                                                <option value="">---</option>
                                                <option value="all"><?=__("All","wpc")?></option>

                                            </select>
                                            </th></tr>
                                        <?php
                                        foreach($allColors as $color){
                                            ?>
                                            <tr>
                                                <td>
                                                    <?=$color["name"];?>
                                                </td>
                                                <td style="background-color: <?=$color["value"];?>;width: 20px;height: 20px"></td>
                                                <td style="text-align: center">
                                                    <?php $checked_color=in_array($color["name"]."|".$color["value"],$check_color)?"checked":""; ?>
                                                    <input <?=$checked_color;?> class="color_checkbox"  type="checkbox" value="<?=$color["name"]?>|<?=$color["value"];?>" name="wpc_colors[<?=$baseLayer?>][<?=$term->slug?>][colors][]">
                                                </td>
                                            </tr>
                                        <?php }}?>
                                </table>
                            </td>
                        </tr>
                    <?php }}}?>
        </table>
    <?php }?>
    <?php if(!empty($all_static_layers)){
        foreach($all_static_layers as $m=>$static_layer){
            $all_terms= $post_terms = wp_get_post_terms($postId, $static_layer);
        ?>
            <h2><?php _e('Colors For','wpc')?> <?=wc_attribute_label($static_layer);?></h2>
            <table>
                <tr>
                    <th><?=__("Term Name","wpc");?></th>
                    <th><?=__("Colors","wpc");?></th>
                </tr>
                <?php if(!empty($all_terms)){
                    foreach($all_terms as $term){
                        $checking_value=$term->taxonomy.'|'.$term->term_id;
                        if (has_term(absint($term->term_id), $static_layer, $postId) && (!in_array($term->term_id,$wpc_multicolor_cords) && !in_array($term->term_id,$wpc_no_cords))) {
                            $check_color=isset($allColorsMeta[$static_layer][$term->slug]["colors"]) ? $allColorsMeta[$static_layer][$term->slug]["colors"]:array();
                            ?>
                            <tr>
                                <td><?=$term->name?></td>
                                <td>
                                    <table style="border-bottom: 1px solid #000000" cellspacing="10">
                                        <?php if(!empty($allColors)){
                                            ?>
                                            <tr><th>&nbsp;</th><th>&nbsp;</th><th style="text-align: center"><a class="wpc_selectAllButton" href="#"><?=__('All','wpc');?></a> </th></tr>
                                            <?php
                                            foreach($allColors as $color){
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?=$color["name"];?>
                                                    </td>
                                                    <td style="background-color: <?=$color["value"];?>;width: 20px;height: 20px"></td>
                                                    <td style="text-align: center">
                                                        <?php $checked_color=in_array($color["name"]."|".$color["value"],$check_color)?"checked":""; ?>
                                                        <input <?=$checked_color;?> class="color_checkbox"  type="checkbox" value="<?=$color["name"]?>|<?=$color["value"];?>" name="wpc_colors[<?=$static_layer?>][<?=$term->slug?>][colors][]">
                                                    </td>
                                                </tr>
                                            <?php }}?>
                                    </table>
                                </td>
                            </tr>
                        <?php }}}?>
            </table>
     <?php }}?>
    <?php if(!empty($wpc_cord_layers)){ foreach($wpc_cord_layers as $k=>$layer){
        $all_terms= $post_terms = wp_get_post_terms($postId, $layer);
      //  $no_cord=($wpc_no_cords[$layer])?$wpc_no_cords[$layer]:array();
        ?>
         <h2><?php _e('Colors For','wpc')?> <?=wc_attribute_label($layer);?></h2>
            <table>
                <tr>
                    <th><?=__("Term Name","wpc");?></th>
                    <th><?=__("Colors","wpc");?></th>
                </tr>
                <?php if(!empty($all_terms)){
                 foreach($all_terms as $term){
                     $checking_value=$term->taxonomy.'|'.$term->term_id;
                    if (has_term(absint($term->term_id), $layer, $postId) && (!in_array($term->term_id,$wpc_multicolor_cords) && !in_array($term->term_id,$wpc_no_cords))) {
                        $check_color=isset($allColorsMeta[$layer][$term->slug]["colors"]) ? $allColorsMeta[$layer][$term->slug]["colors"]:array();
                        ?>
                <tr>
                    <td><?=$term->name?></td>
                    <td>
                        <table style="border-bottom: 1px solid #000000" cellspacing="10">
                            <?php if(!empty($allColors)){
                                ?>
                                <tr><th>&nbsp;</th><th>&nbsp;</th><th style="text-align: center"><a class="wpc_selectAllButton" href="#"><?=__('All','wpc');?></a> </th></tr>
                            <?php
                                foreach($allColors as $color){
                                ?>
                                 <tr>
                                    <td>
                                        <?=$color["name"];?>
                                    </td>
                                     <td style="background-color: <?=$color["value"];?>;width: 20px;height: 20px"></td>
                                     <td style="text-align: center">
                                         <?php $checked_color=in_array($color["name"]."|".$color["value"],$check_color)?"checked":""; ?>
                                         <input <?=$checked_color;?> class="color_checkbox"  type="checkbox" value="<?=$color["name"]?>|<?=$color["value"];?>" name="wpc_colors[<?=$layer?>][<?=$term->slug?>][colors][]">
                                     </td>
                                 </tr>
                            <?php }}?>
                        </table>
                    </td>
                </tr>
            <?php }}}?>
            </table>
     <?php }}?>
</form>

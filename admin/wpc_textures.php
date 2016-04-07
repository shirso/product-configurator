<?php
if (!defined('ABSPATH')) exit;
$settings=get_option('wpc_settings');
$allTextures=$settings["texture_data"];
$wpc_cord_layers=get_post_meta($postId,'_wpc_cord_layers',true);
$wpc_no_cords=get_post_meta($postId,'_wpc_no_cords',true);
$edgeLayer=get_post_meta($postId,'_wpc_edge_layer',true);
$settings=get_option('wpc_settings');
$allColors=$settings["texture_data"];
if(is_array($wpc_cord_layers) && !empty($wpc_cord_layers)){?>
    <form id="wpc_textures_form">
    <?php foreach($wpc_cord_layers as $k=>$layer){
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
     <?php } ?>
    </form>
<?php }

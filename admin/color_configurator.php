<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$post_id=$_GET['post'];
$title=get_the_title($post_id);
$base_step=get_post_meta($post_id,'_wpc_color_dependency',true);
$base_attribute= wp_get_post_terms($post_id,$base_step);
$exclude_color=get_post_meta($post_id,'_wpc_exclude_color',true);
$attributes = maybe_unserialize( get_post_meta( $post_id, '_product_attributes', true ) );
$color_config=get_post_meta($post_id,'_wpc_color_config',true);
//print_r($color_config);
$settings=get_option('wpc_settings');
$allColors=$settings["colors_data"];
$variation_attribute_found = false;
if ($attributes) {

    foreach ($attributes as $attribute) {

        if (isset($attribute['is_variation'])) {
            $variation_attribute_found = true;
            break;
        }
    }
}
?>
<h2><?php _e('Color setting for','wpc') ?> <?=$title?></h2>
<div class="step-images">
    <h3><?php _e('Step Color','wpc'); ?></h3>
    <form action="" method="post" id="step_color_form" data-id="<?=$post_id?>">
        <?php if ($variation_attribute_found) {
            for($i=0;$i<count($base_attribute);$i++){
                ?>
                <h4 style="text-align: center;border-bottom: 1px solid #000"><?=$base_attribute[$i]->name?></h4>
                <?php  foreach ($attributes as $attribute) {
                    if ($base_attribute[$i]->taxonomy==$attribute['name']) {
                        continue;
                    }
                    if (in_array($attribute['name'],$exclude_color)) {
                        continue;
                    }
                    if ($attribute["is_taxonomy"]) {
                        $post_terms = wp_get_post_terms($post_id, $attribute['name']);
                        ?>
                        <table cellspacing="10" cellpadding="5">
                            <tr>
                                <td colspan="3" style="text-align: center"> <?php echo wc_attribute_label($attribute['name']) ?></td>
                            </tr>
                            <tr>
                                <th><?php _e('Term Name','wpc'); ?></th>
                                <th><?php _e('Colors','wpc'); ?></th>
                                <th><?php _e('No Color','wpc'); ?></th>
                            </tr>
                            <?php   foreach ($post_terms as $term) {
                                ?>
                                <tr>
                                    <td><?=$term->name?></td>
                                    <td>
                                      <table style="border-bottom: 1px solid #000000" cellspacing="10">
                                          <tr>
                                             <th>&nbsp;</th><th>&nbsp;</th><th><?=_e('Available','wpc')?> <a class="wpc_selectAllButton" href="#">(<?=__('All','wpc');?>)</a>   </th><th><?=_e('Default','wpc')?></th>
                                          </tr>
                                          <?php  foreach($allColors as $color) {
                                              $colorCheckValue=$color["name"].'|'.$color["value"];
                                              $colorDataValue=@$color_config['_wpc_colors'][$base_attribute[$i]->slug][$attribute['name']][$term->slug]['colors'];
                                              $colorDeafultValue=@$color_config['_wpc_colors'][$base_attribute[$i]->slug][$attribute['name']][$term->slug]['defaults'];
                                              //print_r(@$color_config['_wpc_colors'][$base_attribute[$i]->slug]);
                                              $checked=is_array($colorDataValue)&&in_array($colorCheckValue,$colorDataValue)?'checked':'';
                                              $checked_deafult=$colorCheckValue==$colorDeafultValue?'checked':'';
                                              ?>
                                              <tr>
                                                <td><?=$color["name"]?></td>
                                                <td style="background-color: <?=$color["value"];?>;width: 20px;height: 20px"></td>
                                                  <td style="text-align: center">

                                                   <input class="color_checkbox" <?=$checked?> type="checkbox" value="<?=$color["name"]?>|<?=$color["value"];?>" name="_wpc_colors[<?=$base_attribute[$i]->slug?>][<?=$attribute['name']?>][<?=$term->slug?>][colors][]">
                                                  </td>
                                                  <td style="text-align: center">
                                                      <input <?=$checked_deafult?> type="checkbox" value="<?=$color["name"]?>|<?=$color["value"];?>" name="_wpc_colors[<?=$base_attribute[$i]->slug?>][<?=$attribute['name']?>][<?=$term->slug?>][defaults]">
                                                  </td>

                                              </tr>
                                        <?php }?>
                                      </table>
                                    </td>
                                    <td style="text-align: center">
                                        <?php $checkedValue=@$color_config['_wpc_colors'][$base_attribute[$i]->slug][$attribute['name']][$term->slug]["no_color"]==1?"checked":"";?>
                                        <input <?=$checkedValue?> type="checkbox" value="1" name="_wpc_colors[<?=$base_attribute[$i]->slug?>][<?=$attribute['name']?>][<?=$term->slug?>][no_color]">
                                    </td>

                                </tr>
                                <?php
                            }?>
                        </table>
                        <?php
                    }
                }
            }
        } ?>
        <button type="submit" class="button button-primary"><?php _e('Save Colors','wpc') ?></button>
    </form>
</div>



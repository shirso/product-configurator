<?php
if (!defined('ABSPATH')) exit;

$post_id = $_GET['post'];
$title = get_the_title($post_id);
$base_step=get_post_meta($post_id,'_wpc_color_dependency',true);
$base_attribute= wp_get_post_terms($post_id,$base_step);
$texture_terms = get_post_meta($post_id, '_wpc_texture_term', true);
$texture_config=get_post_meta($post_id,'_wpc_texture_config',true);
$attributes = maybe_unserialize( get_post_meta( $post_id, '_product_attributes', true ) );
?>
<h2><?php _e('Texture setting for', 'wpc') ?> <?= $title ?></h2>
<div class="step-images">
    <form action="" method="post" id="step_texture_form" data-id="<?= $post_id ?>">

        <?php
        for($i=0;$i<count($base_attribute);$i++){
            ?>
            <h4 style="text-align: center;border-bottom: 1px solid #000"><?=$base_attribute[$i]->name?></h4>
            <?php
        if ($texture_terms && is_array($texture_terms)) {
            foreach ($texture_terms as $term) { ?>
                <?php $term_explode = explode('|', $term);
                $taxonomy = $term_explode[0];
                $term_slug = $term_explode[1];
                $term_details = get_term_by('slug', $term_slug, $taxonomy);
                $term_name = $term_details->name;
                $wpc_settings = get_option('wpc_settings');
                $texture_data = $wpc_settings['texture_data'];
                ?>
                <h3><?= $term_name . ' ' . __('for', 'wpc') . ' ' . wc_attribute_label($taxonomy); ?> </h3>
                <table>
                    <tr>
                        <th><?php _e('Name','wpc')?></th><th><?php _e('Image','wpc')?></th><th><?php _e('Not Available','wpc')?></th><th><?php _e('Default','wpc')?></th>
                    </tr>
                    <?php
                    foreach ($texture_data as $td) {
                        ?>
                            <tr>
                                <td>
                                    <label><?=$td['name']?></label>
                                </td>
                                <td>
                                    <input type="text" value="<?=@$texture_config['_wpc_textures'][$base_attribute[$i]->slug][$taxonomy][$term_details->term_id][$td['name']]['texture_image']?>" name="_wpc_textures[<?=$base_attribute[$i]->slug?>][<?=$taxonomy?>][<?=$term_details->term_id?>][<?=$td['name']?>][texture_image]">
                                    <button class="button button-secondary background-upload">Upload</button>
                                </td>
                                <td style="text-align: center">
                                    <?php $checkedValue=@$texture_config['_wpc_textures'][$base_attribute[$i]->slug][$taxonomy][$term_details->term_id][$td['name']]['no_texture']==1?"checked":"";?>
                                    <input type="checkbox" <?=$checkedValue?> value="1" name="_wpc_textures[<?=$base_attribute[$i]->slug?>][<?=$taxonomy?>][<?=$term_details->term_id?>][<?=$td['name']?>][no_texture]"/>
                                </td>
                                <td style="text-align: center">
                                    <?php $checkedValue=@$texture_config['_wpc_textures'][$base_attribute[$i]->slug][$taxonomy][$term_details->term_id][$td['name']]['default']==1?"checked":"";?>
                                    <input <?=$checkedValue?> type="checkbox" value="1" name="_wpc_textures[<?=$base_attribute[$i]->slug?>][<?=$taxonomy?>][<?=$term_details->term_id?>][<?=$td['name']?>][default]">
                                </td>
                            </tr>
                        <?php
                    }
                    ?>
                </table>
            <?php }
        } }?>
        <button type="submit" name="wpc_texture" class="button button-primary"><?php _e('Save Setting','wpc') ?></button>
    </form>
</div>
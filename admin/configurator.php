<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$post_id=$_GET['post'];
$title=get_the_title($post_id);
$base_step=get_post_meta($post_id,'_wpc_color_dependency',true);
$base_attribute= wp_get_post_terms($post_id,$base_step);
$exclude_image=get_post_meta($post_id,'_wpc_exclude_image',true);
$attributes = maybe_unserialize( get_post_meta( $post_id, '_product_attributes', true ) );
$image_config=get_post_meta($post_id,'_wpc_image_config',true);
print_r($image_config);
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
<h2><?php _e('Image and Color setting for','wpc') ?> <?=$title?></h2>

<div class="step-images">
    <h3><?php _e('Step Images','wpc'); ?></h3>

        <form action="" method="post" id="step_image_form" data-id="<?=$post_id?>">
        <?php if ($variation_attribute_found) {
            for($i=0;$i<count($base_attribute);$i++){ ?>
                <h4 style="text-align: center;border-bottom: 1px solid #000"><?=$base_attribute[$i]->name?></h4>
                <?php  foreach ($attributes as $attribute) {
                    if ($base_attribute[$i]->taxonomy==$attribute['name']) {
                        continue;
                    }
                    if (in_array($attribute['name'],$exclude_image)) {
                        continue;
                    }
                    ?>
                    <?php if ($attribute["is_taxonomy"]) {
                        $post_terms = wp_get_post_terms($post_id, $attribute['name']);

                        ?>

                    <table>
                        <tr>
                            <td colspan="3" style="text-align: center"> <?php echo wc_attribute_label($attribute['name']) ?></td>
                        </tr>
                        <tr>
                            <th><?php _e('Term Name','wpc'); ?></th>
                            <th><?php _e('Base Image','wpc'); ?></th>
                            <th><?php _e('Texture Image','wpc'); ?></th>
                        </tr>
                      <?php  foreach ($post_terms as $term) { ?>
                          <tr>
                              <td><?=$term->name?></td>
                              <td>
                                  <?php //print_r(@$image_config['_wpc_images'][$base_attribute[$i]->slug][$attribute['name']][$term->slug]); ?>
                                  <input type="text" value="<?=@$image_config['_wpc_images'][$base_attribute[$i]->slug][$attribute['name']][$term->slug]['background_image']?>" name="_wpc_images[<?=$base_attribute[$i]->slug?>][<?=$attribute['name']?>][<?=$term->slug?>][background_image]" />
                                  <button class="button button-secondary background-upload" ><?php _e('Upload', 'wpc') ?></button></td>
                              </td>
                              <td>
                                  <input type="text" value="<?=@$image_config['_wpc_images'][$base_attribute[$i]->slug][$attribute['name']][$term->slug]["foreground_image"]?>" name="_wpc_images[<?=$base_attribute[$i]->slug?>][<?=$attribute['name']?>][<?=$term->slug?>][foreground_image]" />
                                  <button class="button button-secondary background-upload" ><?php _e('Upload', 'wpc') ?></button></td>
                              </td>
                          </tr>
                            <?php }?>
                    </table>
                        <?php }?>
                    <?php }?>
            <?php }}?>
            <button type="submit" class="button button-primary"><?php _e('Save Images','wpc') ?></button>
        </form>

</div>

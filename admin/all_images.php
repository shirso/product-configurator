<?php
if (!defined('ABSPATH')) exit;
$post_id = absint($_GET['post']);
$taxonomy=esc_html($_GET["taxonomy"]);
$term_id=absint($_GET["term"]);
//print_r()
$attributes = maybe_unserialize(get_post_meta($post_id, '_product_attributes', true));
?>
<script type="text/javascript">
    var wpc_image_page=true,
        postId=<?=$post_id;?>;
</script>
<div id="wpc_all_images">
    <h3><?=__('Base & Edge Images','wpc')?></h3>
    <section>
        <div id="wpc_base_edge">
            <form id="wpc_base_edge_form">
            <table>
                <tr>
                    <td><?=__('Base Images','wpc')?></td>
                    <td>
                       <table>
                           <tr>
                               <th><?=__('Base','wpc')?></th>
                               <th><?=__('Texture','wpc')?></th>
                           </tr>
                           <tr>
                               <td>
                               <input type="text" name="wpc_base_image_base[<?=$term_id;?>]" id="wpc_base_image_base" value="<?=get_post_meta($post_id,'_wpc_base_image_base',true);?>">
                               <input type="button" class="button button-secondary wpc_image_upload" data-field="wpc_base_image_base" value="<?=__('Upload','wpc');?>">
                               </td>
                           <td>
                               <input type="text" name="wpc_base_image_texture[<?=$term_id;?>]" id="wpc_base_image_texture" value="<?=get_post_meta($post_id,'_wpc_base_image_texture',true);?>">
                               <input type="button" class="button button-secondary wpc_image_upload" data-field="wpc_base_image_texture" value="<?=__('Upload','wpc');?>">
                           </td>
                           </tr>
                       </table>
                    </td>
                </tr>
                <tr>
                    <td><?=__('Edge Images','wpc')?></td>
                    <td>
                        <table>
                            <tr>
                                <th><?=__('Base','wpc')?></th>
                                <th><?=__('Texture','wpc')?></th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="wpc_edge_image_base[<?=$term_id;?>]" id="wpc_edge_image_base" value="<?=get_post_meta($post_id,'_wpc_edge_image_base',true);?>">
                                    <input type="button" class="button button-secondary wpc_image_upload" data-field="wpc_edge_image_base" value="<?=__('Upload','wpc');?>">
                                </td>
                                <td>
                                    <input type="text" name="wpc_edge_image_texture[<?=$term_id;?>]" id="wpc_edge_image_texture" value="<?=get_post_meta($post_id,'_wpc_edge_image_texture',true);?>">
                                    <input type="button" class="button button-secondary wpc_image_upload" data-field="wpc_edge_image_texture" value="<?=__('Upload','wpc');?>">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            </form>
        </div>
    </section>
    <h3><?=__('Cord Layers','wpc');?></h3>
    <section id="wpc_cord_layers">
        <h2><?=__('Choose Cord Layers','wpc');?></h2>
        <form id="wpc_cord_layers_form">
        <?php $wpc_cord_layers=get_post_meta($post_id,'_wpc_cord_layers',true);
        if (isset($attributes) && !empty($attributes)){
        foreach ($attributes as $attr) {
            if (!$attr['is_variation']) {
                continue;
            }
            $checked=is_array($wpc_cord_layers) && in_array($attr['name'],$wpc_cord_layers) ? 'checked' : "";
            ?>

            <p class="form-field">
                <label for="wpc_cord_layers_<?=$attr['name'];?>"><input <?=$checked;?> value="<?=$attr['name'];?>" name="wpc_cord_layers[]" type="checkbox" id="wpc_cord_layers_<?=$attr['name'];?>"> <?= esc_html(wc_attribute_label($attr['name'])) ?></label>
            </p>
       <?php }} ?>
        </form>
    </section>
    <h3><?=__('MultiColor Cords','wpc')?></h3>
    <section>
        <div id="wpc_multicolor_cords">

        </div>
    </section>
    <h3><?=__('Cord Images','wpc')?></h3>
    <section>
        <div id="wpc_cord_images">

        </div>
    </section>
    <h3><?=__("Colors",'wpc')?></h3>
    <section>
        <div id="wpc_colors">

        </div>
    </section>
    <h3><?=__("Multi Colors",'wpc');?></h3>
    <section>
        <div id="wpc_textures">

        </div>
    </section>
    <h3><?=__('MultiColor Cord Images','wpc')?></h3>
    <section>
        <div id="wpc_multicolor_images">

        </div>
    </section>
</div>
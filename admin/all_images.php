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
        postId=<?=$post_id;?>,
        termId=<?=$term_id;?>,
        taxonomy='<?=$taxonomy;?>';
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
                           <?php
                           $base_base=get_post_meta($post_id,'_wpc_base_image_base_'.$term_id,true);
                           $base_texture=get_post_meta($post_id,'_wpc_base_image_texture_'.$term_id,true);
                           ?>
                           <tr>
                               <th><?=__('Base','wpc')?></th>
                               <th><?=__('Texture','wpc')?></th>
                           </tr>
                           <tr>
                               <td>

                               <input type="text" name="wpc_base_image_base" id="wpc_base_image_base" value="<?=@$base_base?>">
                               <input type="button" class="button button-secondary wpc_image_upload" data-field="wpc_base_image_base" value="<?=__('Upload','wpc');?>">
                               </td>
                           <td>
                               <input type="text" name="wpc_base_image_texture" id="wpc_base_image_texture" value="<?=@$base_texture?>">
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
                            <?php
                            $edge_base=get_post_meta($post_id,'_wpc_edge_image_base_'.$term_id,true);
                            $edge_texture=get_post_meta($post_id,'_wpc_edge_image_texture_'.$term_id,true);
                            ?>
                            <tr>
                                <th><?=__('Base','wpc')?></th>
                                <th><?=__('Texture','wpc')?></th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" name="wpc_edge_image_base" id="wpc_edge_image_base" value="<?=@$edge_base;?>">
                                    <input type="button" class="button button-secondary wpc_image_upload" data-field="wpc_edge_image_base" value="<?=__('Upload','wpc');?>">
                                </td>
                                <td>
                                    <input type="text" name="wpc_edge_image_texture" id="wpc_edge_image_texture" value="<?=@$edge_texture;?>">
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
<?php
if (!defined('ABSPATH')) exit;
$post_id = absint($_GET['post']);
$available_models=get_post_meta($post_id,'_wpc_available_models',true);
$taxonomy=esc_html($_GET["taxonomy"]);
$term_id=absint($_GET["term"]);
$attributes = maybe_unserialize(get_post_meta($post_id, '_product_attributes', true));
$termDetails=get_term_by("id",$term_id,$taxonomy);
?>
<?php if(in_array($term_id,$available_models)){?>
<h1><?=__("Image and Color configuration for","wpc")?> "<?=$termDetails->name;?>"</h1>
<?php }else{?>
    <h1><?=__("Color configuration for","wpc")?> "<?=$termDetails->name;?>"</h1>
<?php }?>
<script type="text/javascript">
    <?php if(in_array($term_id,$available_models)){?>
    var wpc_image_page=true,
        postId=<?=$post_id;?>,
        termId=<?=$term_id;?>,
        taxonomy='<?=$taxonomy;?>';
    <?php }else{?>
    var wpc_only_color=true,
        postId=<?=$post_id;?>,
        termId=<?=$term_id;?>,
        taxonomy='<?=$taxonomy;?>';
    <?php }?>
</script>
<div id="wpc_all_images">
    <?php if(in_array($term_id,$available_models)){?>
    <h3><?=__('Base & Edge Images','wpc')?></h3>
    <section>
        <div id="wpc_base_edge">
            <form id="wpc_base_edge_form">
            <table class="shop_table_responsive">
                <tr>
                    <td><?=__('Base Images','wpc')?></td>
                    <td>
                       <table class="shop_table_responsive">
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
              <?php $all_static_cords=get_post_meta($post_id,"_wpc_static_layers",true);
              $all_static_image=get_post_meta($post_id,"_wpc_static_images_".$term_id,true);
              $all_not_required=get_post_meta($post_id,"_wpc_not_require_".$term_id,true);
              ?>
                <?php if(!empty($all_static_cords)){
                    foreach($all_static_cords as $cord){
                    ?>
                     <tr>
                         <td><?=wc_attribute_label($cord)?> <?=__("Images","wpc");?></td>
                         <td>
                             <table class="shop_table_responsive">
                                 <tr>
                                     <th><?=__('Base','wpc')?></th>
                                     <th><?=__('Texture','wpc')?></th>
                                 </tr>
                                 <tr>
                                     <td>

                                         <input type="text" name="wpc_static_images[<?=$cord?>][base]" id="wpc_static_images_<?=$cord?>_base" value="<?=@$all_static_image[$cord]["base"]?>">
                                           <input type="button" class="button button-secondary wpc_image_upload" data-field="wpc_static_images_<?=$cord?>_base" value="<?=__('Upload','wpc');?>">
                                     </td>
                                     <td>
                                         <input type="text" name="wpc_static_images[<?=$cord?>][texture]" id="wpc_static_images_<?=$cord?>_texture" value="<?=@$all_static_image[$cord]["texture"]?>">
                                         <input type="button" class="button button-secondary wpc_image_upload" data-field="wpc_static_images_<?=$cord?>_texture" value="<?=__('Upload','wpc');?>">
                                     </td>
                                 </tr>
                                 <tr>
                                     <td>&nbsp;</td>
                                     <td>
                                         <?php  $checked=isset($all_not_required[$cord])?"checked":""; ?>
                                         <input value="1" <?=$checked;?> id="not_require_<?=$cord?>" name="wpc_not_require[<?=$cord?>]" type="checkbox" class="checkbox">
                                         <label for="not_require_<?=$cord?>" class="description"><?=__("This Step is Optional","wpc")?></label>
                                     </td>

                                 </tr>
                              </table>
                        </td>
                     </tr>

                <?php }}?>
            </table>
            </form>
        </div>
    </section>
    <?php }?>
    <?php if(in_array($term_id,$available_models)){?>
    <h3><?=__('Cord Images','wpc')?></h3>
    <section>
        <div id="wpc_cord_images">

        </div>
    </section>
    <?php }?>
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
    <?php if(in_array($term_id,$available_models)){?>
    <h3><?=__('MultiColor Cord Images','wpc')?></h3>
    <section>
        <div id="wpc_multicolor_images">

        </div>
    </section>
    <?php }?>
</div>
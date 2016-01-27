<?php
if (!defined('ABSPATH')) exit;
$post_id = absint($_GET['post']);
$attributes = maybe_unserialize(get_post_meta($post_id, '_product_attributes', true));
?>
<script type="text/javascript">
    var wpc_image_page=true,
        postId=<?=$post_id;?>;
</script>
<div class="panel woocommerce_options_panel wc-metaboxes-wrapper">
 <h2><?=__('Choose Cord Layers','wpc');?></h2>
<?php
$wpc_cord_layers=get_post_meta($post_id,'_wpc_cord_layers',true);
$wpc_cord_layers=!empty($wpc_cord_layers) ? $wpc_cord_layers :array();
if (isset($attributes) && !empty($attributes)) {
foreach ($attributes as $attr) {
$checked=in_array($attr['name'],$wpc_cord_layers) ? 'checked' : "";
    ?>
   <form id="wpc_cord_layers_form">
    <p class="form-field">
        <label for="wpc_cord_layers_<?=$attr['name'];?>"><input <?=$checked;?> value="<?=$attr['name'];?>" name="wpc_cord_layers[]" type="checkbox" id="wpc_cord_layers_<?=$attr['name'];?>"> <?= esc_html(wc_attribute_label($attr['name'])) ?></label>
        </p>
 <?php }} ?>
    <p class="form-field">
        <input type="button" class="button button-default" id="wpc_cord_layers_save" value="<?=__('Save','wpc')?>">
    </p>
   </form>
</div>
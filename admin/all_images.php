<?php
if (!defined('ABSPATH')) exit;
$post_id = absint($_GET['post']);
$attributes = maybe_unserialize(get_post_meta($post_id, '_product_attributes', true));
?>
<script type="text/javascript">
    var wpc_image_page=true,
        postId=<?=$post_id;?>;
</script>
<div id="wpc_all_images">
    <h3><?=__('Base & Edge','wpc')?></h3>
    <section>
        <div id="wpc_base_edge">
         <p class="form-field">
           <label for="wpc_color_dependency"><?=__('Choose Base Layer','wpc')?></label>
             <select name="_wpc_color_dependency" id="wpc_color_dependency">
                 <option value="">---</option>
                 <?php $check_depenedency = get_post_meta($post_id, '_wpc_color_dependency', true); if (isset($attributes) && !empty($attributes)){ foreach ($attributes as $attr) {
                     if (!$attr['is_variation']) {
                         continue;
                     }
                     ?>
                     <?php $checked = $attr['name'] == $check_depenedency ? 'selected' : ''; ?>
                     <option <?=$checked;?> value="<?= $attr['name'] ?>"> <?= esc_html(wc_attribute_label($attr['name'])) ?></option>
                 <?php }}?>
             </select>
         </p>
            <p class="form-field">
                <label for="wpc_base_color_dependency"><?=__('Choose Base Color Layer','wpc')?></label>
                <select name="_wpc_base_color_dependency" id="wpc_base_color_dependency">
                    <option value="">---</option>
                    <?php $check_depenedency = get_post_meta($post_id, '_wpc_base_color_dependency', true); if (isset($attributes) && !empty($attributes)){ foreach ($attributes as $attr) {
                        if (!$attr['is_variation']) {
                            continue;
                        }
                        ?>
                        <?php $checked = $attr['name'] == $check_depenedency ? 'selected' : ''; ?>
                        <option <?=$checked;?> value="<?= $attr['name'] ?>"> <?= esc_html(wc_attribute_label($attr['name'])) ?></option>
                    <?php }}?>
                </select>
            </p>
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
    <h3><?=__('MultiColor Cord Images','wpc')?></h3>
    <section>
        <div id="wpc_multicolor_images">

        </div>
    </section>
</div>
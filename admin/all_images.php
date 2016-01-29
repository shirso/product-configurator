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
    <h3><?=__('Cord Layers','wpc');?></h3>
    <section id="wpc_cord_layers">
        <h2><?=__('Choose Cord Layers','wpc');?></h2>
        <form id="wpc_cord_layers_form">
        <?php $wpc_cord_layers=get_post_meta($post_id,'_wpc_cord_layers',true);
        if (isset($attributes) && !empty($attributes)){
        foreach ($attributes as $attr) {
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

    </section>
    <h3><?=__('Base & Edge','wpc')?></h3>
    <section>

    </section>
    <h3><?=__('Cord Images','wpc')?></h3>
    <section>

    </section>
    <h3><?=__('MultiColor Cord Images','wpc')?></h3>
    <section>

    </section>
</div>
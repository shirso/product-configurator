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
    <section>

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
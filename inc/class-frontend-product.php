<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
if (!class_exists('WPC_Frontend_Product')) {
    class WPC_Frontend_Product
    {
        public function __construct()
        {
            global $post, $wpdb, $product, $woocommerce;
            add_filter('body_class', array(&$this, 'add_class'));
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
            add_action('woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 10);
            add_action('template_redirect',array(&$this,'remove_main_image'));
            add_filter( 'woocommerce_loop_add_to_cart_link', array(&$this, 'add_to_cart_cat_text'), 10, 2 );
        }
        public function remove_main_image(){
            global $post;
            if (wpc_enabled($post->ID)) {
                remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
                add_action('woocommerce_before_single_product_summary', array(&$this, 'add_product_designer'), 20);
            }
        }
        public function add_to_cart_cat_text( $handler, $product ) {

            if( wpc_enabled( $product->id ) ) {

                return sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button product_type_%s">%s</a>',
                    esc_url( get_permalink($product->id) ),
                    esc_attr( $product->id ),
                    esc_attr( $product->get_sku() ),
                    esc_attr( $product->product_type ),
                    esc_html( __('Configure','wpc') )
                );
            }

            return $handler;

        }

        public function add_class($classes)
        {
            global $post;
            if (wpc_enabled($post->ID)) {
                $classes[] = 'wpc-body-product';
            }
            return $classes;
        }

        public function add_product_designer()
        {
            global $post, $wpdb, $product, $woocommerce;

            if (wpc_enabled($product->id) && $product->has_attributes()) {
                WPC_Scripts_Styles::$add_script = true;
                $attributes =  $attributes=maybe_unserialize(get_post_meta($post->ID, '_product_attributes', true));
                $color_config = get_post_meta($post->ID, '_wpc_color_config', true);
                $instruction = get_post_meta($post->ID, '_wpc_instructions',true);
                $variations= $product->get_variation_attributes();
                ?>
                <div class="row" id="wpc_main_container">
                        <div class="col-sm-6" id="cnvs">
                            <div id="wpc_product_stage">
                                <canvas></canvas>
                            </div>
                            <p class="wpc_instructions"><i class="fa fa-info-circle"></i> <?=nl2br(@$instruction['wpc_general'])?></p>
                        </div>
                    <div class="col-sm-6" id="attribute-tabs">
                        <input type="hidden" name="wpc_extra_item[wpc_product_image_data]" class="wpc_extra_item"
                               id="wpc_product_image_data"/>
                        <ul>
                            <?php if(isset($attributes) && !empty($attributes)){
                                    foreach ($attributes as $name => $options) {
                                ?>
                                        <li><a data-attribute="<?= $name ?>"
                                               href="#wpc_<?= $name ?>"><?php echo wc_attribute_label($name); ?></a></li>
                            <?php }}?>
                        </ul>
                        <?php if(isset($attributes) && !empty($attributes)){
                                foreach ($attributes as $name => $options) {
                                    $default_value=$product->get_variation_default_attribute($name);
                             ?>
                                    <input type="hidden" name="wpc_extra_item[<?= $name ?>]" id="wpc_extra_item_<?= $name ?>"
                                           class="wpc_extra_item"/>
                                     <div id="wpc_<?= $name ?>" class="wpc-tb-all" data-attribute="<?= $name ?>">
                                         <a id="wpc_up_<?= $name ?>" data-scroll="wpc_down_<?= $name ?>" href="#" class="wpc_scroll_down wpc-scroll">
                                             <i class="fa fa-arrow-circle-down"></i>
                                         </a>
                                          <div class="seclect">
                                              <h3><?=wc_attribute_label($name);?></h3>
                                              <p class="wpc_instructions"><i class="fa fa-info-circle"></i> <?=nl2br(@$instruction[$name])?></p>
                                              <?php echo $this->get_variation_items($name, $options, $color_config, $product->id,$default_value); ?>
                                          </div>
                                         <a id="wpc_down_<?= $name ?>" data-scroll="wpc_up_<?= $name ?>" href="#" class="wpc_scroll_up wpc-scroll">
                                             <i class="fa fa-arrow-circle-up"></i>
                                          </a>
                                     </div>
                                <?php }}?>
                    </div>
                </div>

                <?php
            }

        }
        private function get_variation_items($attribute_name,$options,$color_config, $productId,$default_value){
            $orderby = wc_attribute_orderby($attribute_name);
            $attribute_type=get_variation_attribute_type($attribute_name);

            switch ($orderby) {
                case 'name' :
                    $args = array('orderby' => 'name', 'hide_empty' => false, 'menu_order' => false);
                    break;
                case 'id' :
                    $args = array('orderby' => 'id', 'order' => 'ASC', 'menu_order' => false, 'hide_empty' => false);
                    break;
                case 'menu_order' :
                    $args = array('menu_order' => 'ASC', 'hide_empty' => false);
                    break;
            }
            $terms  = get_terms($attribute_name, $args);
            ob_start();
            ?>
            <ul class="attribute_loop">
                <?php if(isset($terms) && !empty($terms)){
                    foreach ($terms as $term) {
                       if(has_term(absint($term->term_id), $attribute_name, $productId)){
                    ?>
                <li class="wpc-variation wpc_term_<?= $term->slug ?>"
                    data-att="<?= $attribute_name ?>" data-attvalue="<?= $term->slug; ?>">
                    <?php if($attribute_type=="image"){
                        $attr_image = get_option('_wpc_variation_attr_image_' . $term->term_id);
                        $available_models=get_post_meta($productId,'_wpc_available_models',true);
                        $extraClass=in_array($term->term_id,$available_models)?"wpc_available_model":"";
                        ?>
                        <a href="#" data-display="<?=$term->name?>" class="wpc_terms <?=$extraClass?> <?= $attribute_name . '_' . $term->slug ?>"
                           data-attribute="<?= $attribute_name ?>" data-term="<?= $term->slug ?>">
                            <img src="<?= $attr_image ?>"
                                 title="<?= $term->name ?>"/><span><?= $term->name ?></span>
                        </a>
                        <?php if($term->slug==$default_value){echo '<i class="fa fa-check"></i>';} ?>
                    <?php }else{?>
                        <button
                            class="wpc_terms  <?= $attribute_name . '_' . $term->slug ?>"
                            data-attribute="<?= $attribute_name ?>" data-display="<?=$term->name?>"
                            data-term="<?= $term->slug ?>" data-id="<?=$term->term_id;?>"><?= $term->name ?></button>
                     <?php }?>
                    </li>
                <?php }}}?>
            </ul>
            <?php
        }
    }

    new WPC_Frontend_Product();
}
<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
if (!class_exists('WPC_Frontend_Product')) {
    class WPC_Frontend_Product
    {
        private static $default_model=0;
        private static $colorsMeta=array();
        private static $textureMeta=array();
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

                        <script type="application/javascript">
                            var defaultValues=[];
                           var cords=[];
                           var actualCords=[];
                        </script>
                        <ul id="wpc_nav_buttons">
                            <?php if(isset($attributes) && !empty($attributes)){
                                    foreach ($attributes as $name => $options) {
                                ?>
                                        <li><a data-attribute="<?= $name ?>"
                                               href="#wpc_<?= $name ?>"><?php echo wc_attribute_label($name); ?></a></li>
                            <?php }}?>
                            <li><a href="#wpc_finish_product_builder"><?=__('Finish','wpc')?></a></li>
                        </ul>
                        <?php if(isset($attributes) && !empty($attributes)){
                                foreach ($attributes as $name => $options) {
                                    $default_value=$product->get_variation_default_attribute($name);
                             ?>
                                    <script type="application/javascript">
                                        defaultValues.push({attribute:'<?=$name?>',term:'<?=$default_value?>'});
                                    </script>
                                    <input type="hidden" name="wpc_extra_item[<?= $name ?>]" id="wpc_extra_item_<?= $name ?>"
                                           class="wpc_extra_item"/>
                                    <input type="hidden" name="wpc_original_item[<?= $name ?>]" id="wpc_original_item_<?= $name ?>" class="wpc_original_item"/>
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
                        <div id="wpc_finish_product_builder">
                            <p class="wpc_instructions"><i class="fa fa-info-circle"></i> <?=nl2br(@$instruction['wpc_finish'])?></p>
                            <?php $selected_design= get_post_meta($post->ID,"_wpc_selected_base",true);
                            if(!empty($selected_design)){
                            ?>
                                <select id="wpc_base_design_options">
                                    <option value="" selected>--<?=__('Choose Design','wpc');?>--</option>
                                    <?php  $args = array('post_type' => 'wpc_base_design',
                                        'tax_query' => array(
                                            array(
                                                'taxonomy' => 'wpc_design_category',
                                                'field' => 'term_id',
                                                'terms' => $selected_design,
                                            ),
                                        ),
                                        'orderby'=>'title'
                                    );
                                    $all_posts=get_posts($args);
                                    if(!empty($all_posts)){
                                    foreach($all_posts as $basedesign){
                                    ?>
                                        <option value="<?=$basedesign->ID ?>"><?=$basedesign->post_title?></option>
                                    <?php }}?>
                                </select>
                            <?php }?>
                            <div id="wpc_final_design">
                                <canvas></canvas>
                            </div>
                            <?php $additional_comment=get_post_meta($post->ID,"_wpc_additional_comment",true);
                            if(!empty($additional_comment)){
                            ?>
                                <div class="row" style="margin-top: 10px">
                                <div class="col-sm-4"> <label><?=__("Additional Instructions","wpc")?></label></div>
                               <div class="col-sm-8">
                                <textarea id="wpc_additional_comment_text" rows="8" style="width: 100%" ></textarea>
                                </div>
                                </div>
                            <?php }?>
                            <button class="wpc_finish_product"><?php _e('Finish Builder', 'wpc'); ?></button>
                            <button class="wpc_finish_reset"><?php _e('Reset All', 'wpc'); ?></button>
                            <div class="single_variation_wrap">

                                <div class="variations_button">
                                    <div class="quantity"><input type="number" size="4" class="input-text qty text" id="wpc_fake_qty" title="Qty" value="1"  step="1" min="1"></div>
                                    <button class="single_add_to_cart_button fusion-button button-default button-small alt" id="wpc_fake_add-to_cart" type="button"><?=__('Add to Cart','wpc')?></button>
                                </div>
                            </div>
                            <input type="hidden" name="wpc_extra_item[wpc_product_emb_type]" class="wpc_extra_item wpc_hidden_emb" id="wpc_product_emb_type" />
                            <input type="hidden" name="wpc_extra_item[wpc_product_emb_image]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_image" id="wpc_product_emb_image" />
                            <input type="hidden" name="wpc_extra_item[wpc_product_emb_text]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_text" id="wpc_product_emb_text" />
                            <input type="hidden" name="wpc_extra_item[wpc_product_emb_font]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_text" id="wpc_product_emb_font" />
                            <input type="hidden" name="wpc_extra_item[wpc_product_emb_font_size]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_text" id="wpc_product_emb_fontsize" />
                            <input type="hidden" name="wpc_extra_item[wpc_product_emb_font_weight]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_text" id="wpc_product_emb_fontweight" />
                            <input type="hidden" name="wpc_extra_item[wpc_product_emb_font_style]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_text" id="wpc_product_emb_fontstyle" />
                            <input type="hidden" name="wpc_extra_item[wpc_product_emb_color]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_text" id="wpc_product_emb_fontcolor" />
                            <input type="hidden" name="wpc_extra_item[wpc_product_emb_position]" class="wpc_extra_item wpc_hidden_emb" id="wpc_product_emb_position" />
                            <input type="hidden" name="wpc_extra_item[wpc_product_emb_angle]" class="wpc_extra_item wpc_hidden_emb" id="wpc_product_emb_angle" />
                            <input type="hidden" name="wpc_extra_item[wpc_product_extra_comment]" class="wpc_extra_item wpc_hidden_emb" id="wpc_product_emb_extra_comment" />
                            <input type="hidden" name="wpc_extra_item[wpc_product_additional_comment]" class="wpc_extra_item" id="wpc_product_additional_comment" />
                        </div>
                    </div>
                    <div class="col-sm-6 plright">
                        <div id="tbstl" class="row">
                            <div class="col-sm-6">
                                <h4><?=__('Chosen Options','wpc')?></h4>
                                <table id="wpc_original_options" class="wpc_options_display">
                                    <?php foreach ($attributes as $name => $options) { ?>
                                    <tr class="wpc_hidden">
                                        <th id="wpc_attributes_names_<?=$name;?>"><?=wc_attribute_label($name)?></th>
                                        <td id="wpc_attributes_values_<?=$name;?>"></td>
                                        <?php } ?>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-6">
                                <h4><?=__('Chosen Colors','wpc')?></h4>
                                <table id="wpc_custom_options" class="wpc_options_display">
                                    <?php foreach ($attributes as $name => $options) { ?>
                                        <tr class="wpc_hidden">
                                            <th id="wpc1_attributes_names_<?=$name;?>"><?=wc_attribute_label($name)?></th>
                                            <td id="wpc1_attributes_values_<?=$name;?>"></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                            <?php $emb_layer=get_post_meta($post->ID,'_wpc_emb_layer',true);
                            if(!empty($emb_layer)){
                            ?>
                                <div class="col-sm-6">
                                    <h4><?=__('Embroidery','wpc')?></h4>
                                    <table class="wpc_options_display">
                                        <tr class="wpc_hidden">
                                            <th><?=__('Type','wpc')?></th>
                                            <td class="wpc_emb_options" id="wpc_emb_options_type"></td>
                                        </tr>
                                        <tr class="wpc_hidden">
                                            <th><?=__('Image','wpc')?></th>
                                            <td class="wpc_emb_options emb_options_for_image" id="wpc_emb_options_image"></td>
                                        </tr>
                                        <tr class="wpc_hidden">
                                            <th><?=__('Text','wpc')?></th>
                                            <td class="wpc_emb_options emb_options_for_text" id="wpc_emb_options_text"></td>
                                        </tr>
                                        <tr class="wpc_hidden">
                                            <th><?=__('Font','wpc')?></th>
                                            <td class="wpc_emb_options emb_options_for_text" id="wpc_emb_options_font"></td>
                                        </tr>
                                        <tr class="wpc_hidden">
                                            <th><?=__('Font Size','wpc')?></th>
                                            <td class="wpc_emb_options emb_options_for_text" id="wpc_emb_options_fontsize"></td>
                                        </tr>
                                        <tr class="wpc_hidden">
                                            <th><?=__('Font Weight','wpc')?></th>
                                            <td class="wpc_emb_options emb_options_for_text" id="wpc_emb_options_fontweight"></td>
                                        </tr>
                                        <tr class="wpc_hidden">
                                            <th><?=__('Font Style','wpc')?></th>
                                            <td class="wpc_emb_options emb_options_for_text" id="wpc_emb_options_fontstyle"></td>
                                        </tr>
                                        <tr class="wpc_hidden">
                                            <th><?=__('Color','wpc')?></th>
                                            <td class="wpc_emb_options emb_options_for_text" id="wpc_emb_options_fontcolor"></td>
                                        </tr>
                                        <tr class="wpc_hidden">
                                            <th><?=__('Rotation','wpc')?></th>
                                            <td class="wpc_emb_options" id="wpc_emb_options_angle"></td>
                                        </tr>
                                        <tr class="wpc_hidden">
                                            <th><?=__('Position','wpc')?></th>
                                            <td class="wpc_emb_options" id="wpc_emb_options_position"></td>
                                        </tr>
                                        <tr class="wpc_hidden">
                                            <th><?=__('Additional Comment','wpc')?></th>
                                            <td class="wpc_emb_options" id="wpc_emb_options_extra_comment"></td>
                                        </tr>
                                    </table>
                                </div>
                            <?php }?>
                        </div>
                    </div>

                </div>
                <script type="text/javascript">
                    var defaultModel=<?=self::$default_model;?>,
                        productId=<?=$product->id;?>,
                        initialModel=<?=self::$default_model;?>;
                </script>
                <?php
                $base_layer=get_post_meta($product->id,"_wpc_base_color_dependency",true);
                $avilable_models=get_post_meta($product->id,"_wpc_available_models",true);
                ?>
                    <?php if(!empty($avilable_models)){ foreach($avilable_models as $k=>$model){ ?>
                                    <div id="wpc_base_images_<?=$model?>" class="wpc_hidden" data-attribute="<?=$base_layer;?>">
                                       <?php
                   $base_image=get_post_meta($product->id,'_wpc_base_image_base_'.$model,true);
                   $texture_image=get_post_meta($product->id,'_wpc_base_image_texture_'.$model,true);
                   ?>
                                        <img src="<?=$base_image?>" class="base_image">
                                        <img src="<?=$texture_image?>" class="texture_image">
                                    </div>
                    <?php }}?>
                <?php
            }

        }
        private function get_variation_items($attribute_name,$options,$color_config, $productId,$default_value){
            $orderby = wc_attribute_orderby($attribute_name);
            $attribute_type=get_variation_attribute_type($attribute_name);
            $base_layer=get_post_meta($productId,"_wpc_base_color_dependency",true);
            $edge_layer=get_post_meta($productId,"_wpc_edge_layer",true);
            $emb_layer=get_post_meta($productId,"_wpc_emb_layer",true);
            $model_layer=get_post_meta($productId,"_wpc_color_dependency",true);
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
                        $selected_class=$term->slug==$default_value ? "atv" : "";
                        $model_class=$attribute_name==$model_layer?"wpc_model":"";
                        ?>
                        <a href="#" data-display="<?=$term->name?>" class="<?=$model_class;?> <?=$selected_class?> <?=$extraClass?> wpc_attribute_button_<?=$attribute_name .'_'.$term->slug;?>"
                           data-attribute="<?= $attribute_name ?>" data-id="<?=$term->term_id;?>" data-term="<?= $term->slug ?>">
                            <img src="<?= $attr_image ?>"
                                 title="<?= $term->name ?>"/><span><?= $term->name ?></span>
                        </a>
                        <?php if($term->slug==$default_value){
                            echo '<i class="fa fa-check"></i>';
                            self::$default_model=$term->term_id;
                            self::$colorsMeta=get_post_meta($productId,"_wpc_colors_".self::$default_model,true);
                            self::$textureMeta=get_post_meta($productId,"_wpc_textures_".self::$default_model,true);
                        } ?>
                    <?php }else{
                        $activeClass=$term->slug==$default_value?"atv":"";
                        $button_class=null;
                        $no_cords=get_post_meta($productId,"_wpc_no_cords",true);
                        $texture_cords=get_post_meta($productId,"_wpc_multicolor_cords",true);
                        $static_layer=get_post_meta($productId,"_wpc_static_layers",true);
                        if(in_array($term->term_id,$no_cords)){
                            $button_class="wpc_no_cords";
                        }
                        if(in_array($term->term_id,$texture_cords)){
                            $button_class="wpc_texture_cords";
                        }
                        if(!in_array($term->term_id,$no_cords) && !in_array($term->term_id,$texture_cords) && $emb_layer!=$attribute_name){
                            $button_class="wpc_color_cords";
                        }
                        if($emb_layer==$attribute_name){
                            $button_class="wpc_emb_buttons";
                            $no_emb=get_post_meta($productId,"_wpc_no_emb",true);
                            if($no_emb==$term->term_id){
                                $button_class="wpc_no_emb";
                            }
                        }
                        if(in_array($attribute_name,$static_layer)){
                            $button_class.=" wpc_static_layer";
                        }
                        ?>
                        <?php
                        if(($button_class=="wpc_color_cords" || $button_class=="wpc_texture_cords") && $activeClass=="atv"){
                            ?>
                            <script type="text/javascript">
                                cords.push({attribute:'<?=$attribute_name;?>',term:'<?=$term->slug;?>'});
                                actualCords.push({attribute:'<?=$attribute_name;?>',term:'<?=$term->slug;?>'});
                            </script>
                       <?php }
                        ?>
                        <button
                            class="wpc_terms <?=$activeClass?> <?=$button_class?> wpc_attribute_button_<?=$attribute_name.'_'.$term->slug?>"
                            data-attribute="<?= $attribute_name ?>" data-display="<?=$term->name?>"
                            data-term="<?= $term->slug ?>" data-id="<?=$term->term_id;?>"><?= $term->name ?></button>
                     <?php }?>
                    </li>
                <?php }}}?>
            </ul>
            <?php
          //  $static_button_class=is_array($static_layer) && in_array($attribute_name,$static_layer)?"static_button":"";
         //   $colorOfThisAttribute=isset(self::$colorsMeta[$attribute_name][$default_value]['colors'])?self::$colorsMeta[$attribute_name][$default_value]['colors']:array();
         //   $textureOfThisAttribute=isset(self::$textureMeta[$attribute_name][$default_value]['textures'])?self::$textureMeta[$attribute_name][$default_value]['textures']:array();
            ?>
        <div class="c-seclect" id="wpc_color_tab_<?=$attribute_name?>">

        </div>
            <div class="c-seclect" id="wpc_texture_tab_<?= $attribute_name ?>">

            </div>
            <?php if($attribute_name==$emb_layer){
                ?>
                <div class="wpc_hidden" id="embroidery_tab">
                    <a href="#" class="wpc_clear_all"><i class="fa fa-refresh"></i> <?=__('Reset','wpc')?></a>
                    <ul id="wpc_emb_buttons">
                    </ul>
                    <div id="wpc_emb_image" class="wpc_hidden wpc_emb_controls">
                        <form id="wpc_image_upload_form"
                              action="<?php echo admin_url('admin-ajax.php') . '?action=wpc_embroidery_image_upload' ?>"
                              method="post" name="wpc_image_upload_form" enctype="multipart/form-data">
                            <div class="choose_file">
                                <span><?php _e('Choose Image', 'wpc') ?> </span>
                                <input name="wpc_image_upload" type="file" id="wpc_image_upload"/>
                            </div>
                        </form>
                        <div class="row wpc_hidden wpc_emb_rotate_buttons">
                            <div class="col-sm-12">
                                <a href="#" id="wpc_reset_angle"><i class="fa fa-refresh"></i> <?=__('Reset','wpc')?></a>
                                <?php
                                $settings=get_option("wpc_settings");
                                $angle=isset($settings["emb_settings"]["rotation_angle"]) && !empty($settings["emb_settings"]["rotation_angle"])?$settings["emb_settings"]["rotation_angle"]:"45";
                                ?>
                                <button data-angle=<?=$angle?> type="button" class="wpc_emb_rotate_only wpc_buttons btn btn-default"><?=__("Rotate","wpc")?></button>
                            </div>
                        </div>
                    </div>
                    <div id="wpc_emb_text" class="wpc_hidden wpc_emb_controls">
                        <div id="wpc_text_add_div">
                            <div id="wpc_text_options" class="wpc_hidden row">
                                <div class="col-sm-4">
                                    <a id="wpc_bold_select" title="<?=__('Bold','wpc');?>" class="bcnt" href=""><i class="fa fa-bold"></i></a>
                                    <a id="wpc_italic_select" title="<?=__('Italic','wpc');?>" class="bcnt" href=""><i class="fa fa-italic"></i></a>
                                </div>
                                <div class="col-sm-8">
                                    <select id="wpc_font_select">

                                    </select>
                                    <select id="wpc_size_select">

                                    </select>
                                </div>

                            </div>
                            <textarea id="wpc_text_add" cols="10" rows="3"></textarea>
                            <button id="wpc_add_text_btn"><?=__("Add Text","wpc")?></button>
                            <div class="row wpc_hidden wpc_emb_rotate_buttons">
                                <div class="col-sm-12">
                                    <a href="#" id="wpc_reset_angle"><i class="fa fa-refresh"></i> <?=__('Reset','wpc')?></a>
                                    <?php
                                    $settings=get_option("wpc_settings");
                                    $angle=isset($settings["emb_settings"]["rotation_angle"]) && !empty($settings["emb_settings"]["rotation_angle"])?$settings["emb_settings"]["rotation_angle"]:"45";
                                    ?>
                                    <button data-angle=<?=$angle?> type="button" class="wpc_emb_rotate_only wpc_buttons btn btn-default"><?=__("Rotate","wpc")?></button>
                                </div>
                            </div>
                            <div id="wpc_emb_colors" class="wpc_hidden">

                            </div>
                        </div>
                    </div>


                    <div id="wpc_emb_postion_buttons" class="btn-group wpc_hidden" role="group">

                    </div>
                    <div id="wpc_emb_extra_comment" class="row">
                        <div class="col-sm-5"><?=__('Additional Comment','wpc')?></div>
                        <div class="col-sm-7">
                            <textarea class="textar" cols="3" id="wpc_emb_extra_comment_text"></textarea>
                            <button class="wpc_button" id="wpc_emb_extra_comment_button"><?=__('Add','wpc')?></button>
                            <button class="wpc_button" id="wpc_emb_extra_comment_button_remove"><?=__('Remove','wpc')?></button>
                        </div>
                    </div>
                </div>
                <?php }?>
       <?php }
    }

    new WPC_Frontend_Product();
}
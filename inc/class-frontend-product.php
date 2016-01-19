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
                $attributes = $product->get_variation_attributes();
                $color_config = get_post_meta($post->ID, '_wpc_color_config', true);
                $instruction = get_post_meta($post->ID, '_wpc_instructions',true);
                // print_r($instruction);

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
                            <?php foreach ($attributes as $name => $options) { ?>
                                <li><a data-attribute="<?= $name ?>"
                                       href="#wpc_<?= $name ?>"><?php echo wc_attribute_label($name); ?></a></li>
                            <?php } ?>
                            <li><a href="#wpc_finish_product_builder"><?=__('Finish','wpc')?></a></li>
                        </ul>

                        <?php foreach ($attributes as $name => $options) { ?>
                            <input type="hidden" name="wpc_extra_item[<?= $name ?>]" id="wpc_extra_item_<?= $name ?>"
                                   class="wpc_extra_item"/>
                            <div id="wpc_<?= $name ?>" class="wpc-tb-all" data-attribute="<?= $name ?>">
                                <a id="wpc_up_<?= $name ?>" data-scroll="wpc_down_<?= $name ?>" href="#" class="wpc_scroll_down wpc-scroll"><i class="fa fa-arrow-circle-down"></i></a>
                                <div class="seclect">
                                    <h3><?php echo wc_attribute_label($name); ?> </h3>

                                    <p class="wpc_instructions"><i class="fa fa-info-circle"></i> <?=nl2br(@$instruction[$name])?></p>
                                    <?php
                                    ?>
                                    <?php echo $this->get_variation_items($name, $options, $color_config, $product->id) ?>
                                </div>
                                <a id="wpc_down_<?= $name ?>" data-scroll="wpc_up_<?= $name ?>" href="#" class="wpc_scroll_up wpc-scroll"><i class="fa fa-arrow-circle-up"></i></a>
                            </div>
                        <?php } ?>
                        <div id="wpc_finish_product_builder">
                            <p class="wpc_instructions"><i class="fa fa-info-circle"></i> <?=nl2br(@$instruction['wpc_finish'])?></p>
                            <select id="wpc_base_design_options">
                                <option value="">--<?=__('Choose Design','wpc');?>--</option>
                                <?php  $base_category = get_post_meta($product->id, '_wpc_selected_base', true);
                                $args = array('post_type' => 'wpc_base_design',
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'wpc_design_category',
                                            'field' => 'term_id',
                                            'terms' => $base_category,
                                        ),
                                    ),
                                    'orderby'=>'title'
                                );
                                $all_posts=get_posts($args);
                                if(!empty($all_posts)){
                                    foreach($all_posts as $basedesign){
                                        ?>
                                        <option value="<?=$basedesign->ID ?>"><?=$basedesign->post_title?></option>
                                    <?php    }
                                }
                                ?>
                            </select>
                            <div id="wpc_final_design">
                                <canvas></canvas>
                            </div>
                            <button class="wpc_finish_product"><?php _e('Finish Builder', 'wpc'); ?></button>
                            <button class="wpc_finish_reset"><?php _e('Reset All', 'wpc'); ?></button>
                            <div class="single_variation_wrap">

                            <div class="variations_button">
                                <div class="quantity"><input type="number" size="4" class="input-text qty text" id="wpc_fake_qty" title="Qty" value="1"  step="1" min="1"></div>
                                <button class="single_add_to_cart_button fusion-button button-default button-small alt" id="wpc_fake_add-to_cart" type="button"><?=__('Add to Cart','wpc')?></button>
                            </div>
                            </div>

                        </div>
                        <input type="hidden" name="wpc_extra_item[wpc_product_emb_type]" class="wpc_extra_item wpc_hidden_emb" id="wpc_product_emb_type" />
                        <input type="hidden" name="wpc_extra_item[wpc_product_emb_image]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_image" id="wpc_product_emb_image" />
                        <input type="hidden" name="wpc_extra_item[wpc_product_emb_text]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_text" id="wpc_product_emb_text" />
                        <input type="hidden" name="wpc_extra_item[wpc_product_emb_font]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_text" id="wpc_product_emb_font" />
                        <input type="hidden" name="wpc_extra_item[wpc_product_emb_font_size]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_text" id="wpc_product_emb_font_size" />
                        <input type="hidden" name="wpc_extra_item[wpc_product_emb_font_weight]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_text" id="wpc_product_emb_font_weight" />
                        <input type="hidden" name="wpc_extra_item[wpc_product_emb_font_style]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_text" id="wpc_product_emb_font_style" />
                        <input type="hidden" name="wpc_extra_item[wpc_product_emb_color]" class="wpc_extra_item wpc_hidden_emb wpc_emb_cart_text" id="wpc_product_emb_color" />
                        <input type="hidden" name="wpc_extra_item[wpc_product_emb_position]" class="wpc_extra_item wpc_hidden_emb" id="wpc_product_emb_position" />
                        <input type="hidden" name="wpc_extra_item[wpc_product_extra_comment]" class="wpc_extra_item wpc_hidden_emb" id="wpc_product_extra_comment" />
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
                            <div class="col-sm-12">
                                <h4><?=__('Embroidery','wpc')?></h4>
                                <table class="wpc_options_display">
                                    <tr>
                                        <th><?=__('Type','wpc')?></th>
                                        <td class="wpc_emb_options" id="wpc_emb__options_type"></td>
                                    </tr>
                                    <tr>
                                        <th><?=__('Image','wpc')?></th>
                                        <td class="wpc_emb_options emb_options_for_image" id="wpc_emb__options_image"></td>
                                    </tr>
                                    <tr>
                                        <th><?=__('Text','wpc')?></th>
                                        <td class="wpc_emb_options emb_options_for_text" id="wpc_emb__options_text"></td>
                                    </tr>
                                    <tr>
                                        <th><?=__('Font','wpc')?></th>
                                        <td class="wpc_emb_options emb_options_for_text" id="wpc_emb__options_font"></td>
                                    </tr>
                                    <tr>
                                        <th><?=__('Font Size','wpc')?></th>
                                        <td class="wpc_emb_options emb_options_for_text" id="wpc_emb__options_fontsize"></td>
                                    </tr>
                                    <tr>
                                        <th><?=__('Font Weight','wpc')?></th>
                                        <td class="wpc_emb_options emb_options_for_text" id="wpc_emb__options_fontweight"></td>
                                    </tr>
                                    <tr>
                                        <th><?=__('Font Style','wpc')?></th>
                                        <td class="wpc_emb_options emb_options_for_text" id="wpc_emb__options_fontstyle"></td>
                                    </tr>
                                    <tr>
                                        <th><?=__('Color','wpc')?></th>
                                        <td class="wpc_emb_options emb_options_for_text" id="wpc_emb__options_fontcolor"></td>
                                    </tr>
                                    <tr>
                                        <th><?=__('Position','wpc')?></th>
                                        <td class="wpc_emb_options" id="wpc_emb__options_position"></td>
                                    </tr>
                                    <tr>
                                        <th><?=__('Additional Comment','wpc')?></th>
                                        <td class="wpc_emb_options" id="wpc_emb__options_extra_comment"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            }

        }

        private function get_variation_items($attribute_name, $options, $color_config, $productId)
        {
            $textures = get_post_meta($productId, '_wpc_texture_config', true);
            $texture_tarm = get_post_meta($productId, '_wpc_texture_term', true);
            $embroidery_step = get_post_meta($productId, '_wpc_embroidery_step', true);
            $embroidery_term = get_post_meta($productId, '_wpc_embroidery_term', true);
            $embroidery_no_term=get_post_meta($productId,'_wpc_emb_no_embroidery',true);
            $wpc_base_color_dependency = get_post_meta($productId, '_wpc_base_color_dependency', true);
            $settings = get_option('wpc_settings');
            $all_textures = $settings['texture_data'];
            $emb_colors=get_post_meta($productId,'_wpc_emb_colors',true);
            $orderby = wc_attribute_orderby($attribute_name);
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
            $terms = get_terms($attribute_name, $args);
            ob_start();
            if (isset($terms->errors)) {
                ?>
                <div
                    class="column"><?php printf(__('No attributes found for this taxonomy. Be sure that they are visible on the product page and you created them via the <a href="%s" target="_blank">Attributes admin page</a>.'), admin_url() . 'edit.php?post_type=product&page=product_attributes'); ?></div>
                <?php return false;
            }
            ?>
            <ul class="attribute_loop"> <?php
                foreach ($terms as $term) {
                    if (!in_array($term->slug, $options)) {
                        continue;
                    }
                    $attr_type = get_option('_wpc_variation_attr_type_' . $term->term_id);
                    $class_type = '';
                    $attr_image = '';
                    $attr_color = '';
                    switch ($attr_type) {
                        case 'None':
                            $class_type = 'wpc_none';
                            break;
                        case 'Color':
                            $class_type = 'wpc_color';
                            $attr_color = get_option('_wpc_variation_attr_' . $term->term_id);
                            break;
                        case 'Image':
                            $class_type = 'wpc_image';
                            $attr_image = get_option('_wpc_variation_attr_' . $term->term_id);
                            break;
                    }
                    ?>
                    <li class="wpc-variation <?= $class_type ?> wpc_term_<?= $term->slug ?>"
                        data-att="<?= $attribute_name ?>" data-attvalue="<?= $term->slug; ?>">
                        <?php
                        if ($attr_type == 'Image') {
                            ?>
                            <a href="#" data-display="<?=$term->name?>" class="wpc_terms <?= $attribute_name . '_' . $term->slug ?>"
                               data-attribute="<?= $attribute_name ?>" data-term="<?= $term->slug ?>">
                                <img src="<?= $attr_image ?>"
                                     title="<?= $term->name ?>"/><span><?= $term->name ?></span>
                            </a>
                            <?php
                        } else {
                            $texture_check = $attribute_name . '|' . $term->slug;
                            $button_class = '';
                            if (in_array($texture_check, $texture_tarm)) {
                                $button_class = 'texture_button';
                            }
                            $select_embroidery_term = $attribute_name . '|' . $term->slug;
                            $class_embroidery = $select_embroidery_term == $embroidery_term ? 'wpc_embroidery' : '';
                            $base_color_class=$wpc_base_color_dependency==$attribute_name?'this_is_base_color':'';
                            $embroidery_buttons=$embroidery_step==$attribute_name && $embroidery_no_term!=$term->slug?'embroidery_buttons':'';
                            $no_embroidery=$term->slug==$embroidery_no_term?'no_embroidery':'';
                            ?>
                            <button
                                class="wpc_terms <?=$embroidery_buttons;?> <?=$no_embroidery;?> <?= $button_class ?> <?= $attribute_name . '_' . $term->slug ?> <?= $class_embroidery ?>"
                                data-attribute="<?= $attribute_name ?>" data-display="<?=$term->name?>"
                                data-term="<?= $term->slug ?>" data-id="<?=$term->term_id;?>"><?= $term->name ?></button>
                            <?php
                            if (is_array($color_config['_wpc_colors']) && !empty($color_config['_wpc_colors'])) {
                                foreach ($color_config['_wpc_colors'] as $k => $v) {
                                    if (isset($v[$attribute_name]) && !empty($v[$attribute_name])) {
                                        ?>
                                        <div class="c-seclect <?= $k . '_' . $attribute_name ?> wpc_hidden">
                                            <?php
                                            if (isset($v[$attribute_name][$term->slug]) && !empty($v[$attribute_name][$term->slug])) {
                                                ?>
                                                <?php if (isset($v[$attribute_name][$term->slug]['colors']) && is_array($v[$attribute_name][$term->slug]['colors']) && !empty($v[$attribute_name][$term->slug]['colors'])) { ?>
                                                    <div>
                                                        <?php

                                                        foreach ($v[$attribute_name][$term->slug]['colors'] as $color) {
                                                            $all = explode('|', $color);
                                                            ?>
                                                            <div class="flclr">
                                                                <div class="change_color insec <?=$base_color_class;?>"
                                                                     style="background: <?= $all[1] ?>"
                                                                     data-all="<?= $all[0] . '|' . $all[1] ?>"
                                                                     data-color="<?= $all[1] ?>"
                                                                     data-term="<?= $term->slug ?>"
                                                                     data-attribute="<?= $attribute_name ?>"
                                                                     data-colorname="<?= $all[0] ?>"
                                                                     data-id="<?= $k . '_' . $attribute_name ?>">
                                                                </div>
                                                                <p><?= $all[0] ?></p>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                <?php
                                            }
                                            ?>

                                        </div>
                                        <?php
                                    }
                                }
                            }

                            //foreach for texture
                            if (isset($textures['_wpc_textures']) && is_array($textures['_wpc_textures']) && !empty($textures['_wpc_textures']) && in_array($texture_check, $texture_tarm)) {
                                foreach ($textures['_wpc_textures'] as $k => $v) {
                                    if (isset($v[$attribute_name]) && !empty($v[$attribute_name])) {
                                        ?>
                                        <div class="c-select_<?= $k . '_' . $attribute_name ?> wpc_hidden">
                                            <div>
                                                <?php if (isset($v[$attribute_name][$term->term_id]) && !empty($v[$attribute_name][$term->term_id])) {
                                                    foreach ($v[$attribute_name][$term->term_id] as $k => $v) {
                                                        // print_r(array_search($k,array_column($all_textures,'name')));
                                                        if (!isset($v['no_texture'])) {
                                                            $index = array_search($k, array_column($all_textures, 'name'));
                                                            $texture_values = $all_textures[$index];
                                                            ?>
                                                            <div class="flclr">
                                                                <div class="insec change_texture"
                                                                     data-term="<?= $term->slug ?>"
                                                                     data-attribute="<?= $attribute_name ?>"
                                                                     data-texture="<?= $k ?>"
                                                                     style='background: url("<?= $texture_values['value'] ?>")'></div>
                                                                <p><?= $texture_values['name'] ?></p>
                                                            </div>
                                                        <?php }
                                                    }
                                                } ?>
                                            </div>
                                        </div>
                                    <?php }
                                }
                            }
                        }
                        ?>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <div class="c-seclect" id="wpc_color_tab_<?= $attribute_name ?>">

            </div>
            <div class="c-seclect" id="wpc_texture_tab_<?= $attribute_name ?>"></div>
            <?php

            if ($embroidery_step == $attribute_name) {
                $fonts = get_option('wpc_settings');
                ?>
                <div class="wpc_hidden" id="embroidery_tab">
                    <a href="#" class="wpc_clear_all"><i class="fa fa-refresh"></i> <?=__('Reset','wpc')?></a>
                    <ul>
                        <li><a data-type="image" class="btab wpc_emb_tabs wpc_buttons" href="#wpc_emb_image"><?=__('Image','wpc')?></a></li>
                        <li><a data-type="text" class="btab wpc_emb_tabs wpc_buttons" href="#wpc_emb_text"><?=__('Text','wpc')?></a></li>
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
                                        <option value="">--Fonts--</option>
                                        <?php foreach ($fonts['google_fonts'] as $gfont) : $gname = str_replace('+', ' ', $gfont); ?>
                                            <option value="<?= $gname ?>"
                                                    style="font-family: <?= $gname ?>"><?= $gname ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <select id="wpc_size_select">
                                        <option value=""><?= __('Size', 'wpc') ?></option>
                                        <?php $options=get_option('wpc_settings'); $font_sizes=isset($options['font_size_data']) ? $options['font_size_data'] : array(); ?>
                                        <?php foreach($font_sizes as $size){  ?> ?>
                                            <option  value="<?=@$size['value']?>"><?=@$size['name'];?></option>
                                        <?php }?>
                                    </select>
                                </div>

                            </div>
                            <textarea id="wpc_text_add" cols="10" rows="3"></textarea>
                            <button id="wpd_add_text_btn">Add Text</button>
                            <div id="wpc_emb_colors" class="wpc_hidden">
                                <?php

                                foreach ($emb_colors as $colorEmb) {
                                    // print_r($v[$attribute_name][$term->slug]['defaults']);
                                    $allEmb = explode('|', $colorEmb);
                                    ?>
                                    <div class="flclr">
                                        <div class="change_color_emb insec"
                                             style="background: <?= $allEmb[1] ?>"
                                             data-all="<?= $allEmb[0] . '|' . $allEmb[1] ?>"
                                             data-colorname="<?=$allEmb[0]?>"
                                             data-color="<?= $allEmb[1] ?>">
                                        </div>
                                        <p><?= $allEmb[0] ?></p>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php $all_positions=get_post_meta($productId,'_wpc_emb_positions',true); ?>
                    <div id="wpc_emb_postion_buttons" class="btn-group wpc_hidden" role="group">
                        <?php foreach($all_positions as $position){ ?>
                            <?php $default_button=isset($position['default'])?'active':''; ?>
                            <button type="button" data-left="<?=$position['left']?>" data-top="<?=$position['top']?>" class="<?=$default_button?> wpc_buttons wpc_emb_btn btn btn-default"><?=$position['name']?></button>
                        <?php }?>
                    </div>
                    <div id="wpc_emb_extra_comment" class="row">
                        <div class="col-sm-5"><?=__('Additional Comment','wpc')?></div>
                        <div class="col-sm-7">
                            <textarea class="textar" cols="3" id="wpc_emb_extra_comment_text"></textarea>
                            <button class="wpc_button" id="wpc_emb_extra_comment_button"><?=__('Add','wpc')?></button>
                        </div>
                    </div>
                </div>

            <?php } ?>

            <?php
        }

    }

    new WPC_Frontend_Product();
}
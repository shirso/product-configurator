<?php
if (!defined('ABSPATH')) exit;
if (!class_exists('WPC_Admin_Product')) {
    class WPC_Admin_Product
    {
        public function __construct()
        {
            add_filter('product_type_options', array(&$this, 'product_type_options'));
            add_filter('woocommerce_product_data_tabs', array(&$this, 'add_product_data_tab'));
            add_action('woocommerce_product_data_panels', array(&$this, 'add_product_data_panel'));
            add_action('woocommerce_process_product_meta', array(&$this, 'save_custom_fields'), 10, 2);
        }

        public function product_type_options($types)
        {
            $types['wpc_check'] = array(
                'id' => '_wpc_check',
                'wrapper_class' => 'show_if_wpc show_if_variable',
                'label' => __('Enable Product Configurator', 'wpc'),
                'description' => __('Enable Product Configurator for this Product.', 'wpc')
            );
            return $types;
        }

        public function add_product_data_tab($tabs)
        {
            $tabs['wpc_instructions'] =array(
                'label'=>__('Step Instructions','wpc'),
                'target'=>'wpc_instructions_tab',
                'class' => array('show_if_wpc_panel')
            );
            $tabs['wpc_default_config'] = array(
                'label' => __('WP Product Configurator', 'wpc'),
                'target' => 'wpc_data_default_configuration',
                'class' => array('show_if_wpc_panel')
            );

            return $tabs;
        }

        public function save_custom_fields($post_id, $post)
        {
            update_post_meta($post_id, '_wpc_check', isset($_POST['_wpc_check']) ? 'yes' : 'no');
            if (isset($_POST['_wpc_default_config'])) {
                update_post_meta($post_id, '_wpc_default_config', $_POST['_wpc_default_config']);
            }
            if (isset($_POST['_wpc_color_dependency'])) {
                update_post_meta($post_id, '_wpc_color_dependency', $_POST['_wpc_color_dependency']);
            }
             if (isset($_POST['_wpc_base_color_dependency'])) {
                update_post_meta($post_id, '_wpc_base_color_dependency', $_POST['_wpc_base_color_dependency']);
                }
            if (isset($_POST['_wpc_exclude_image'])) {
                update_post_meta($post_id, '_wpc_exclude_image', $_POST['_wpc_exclude_image']);
            }
            if (isset($_POST['_wpc_exclude_color'])) {
                update_post_meta($post_id, '_wpc_exclude_color', $_POST['_wpc_exclude_color']);
            }
            if (isset($_POST['_wpc_last_step'])) {
                update_post_meta($post_id, '_wpc_last_step', $_POST['_wpc_last_step']);
            }
            if (isset($_POST['_wpc_embroidery_step'])) {
                update_post_meta($post_id, '_wpc_embroidery_step', $_POST['_wpc_embroidery_step']);
            }
            if (isset($_POST['_wpc_embroidery_term'])) {
                update_post_meta($post_id, '_wpc_embroidery_term', $_POST['_wpc_embroidery_term']);
            }
            if(isset($_POST['wpc_selected_base'])) {
                update_post_meta($post_id, '_wpc_selected_base', $_POST['wpc_selected_base']);
            }
            if(isset($_POST["_wpc_texture_term"])){
                update_post_meta($post_id,'_wpc_texture_term',$_POST['_wpc_texture_term']);
            }
            if(isset($_POST['wpc_instructions'])){
                update_post_meta($post_id,'_wpc_instructions',$_POST['wpc_instructions']);
            }
        }
        public function add_product_data_panel()
        {
            global $wpdb, $post;
            $attributes = maybe_unserialize(get_post_meta($post->ID, '_product_attributes', true));
            $args = array(
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_type' => 'wpc_base_design',
                'post_status' => 'publish'
            );
            $base_images = get_terms('wpc_design_category');  //get_posts($args);
            $options = get_option('wpc_settings');
           // if ($options["pre_configured_product"] == 1) {
                $variation_attribute_found = false;
                if ($attributes) {

                    foreach ($attributes as $attribute) {

                        if (isset($attribute['is_variation'])) {
                            $variation_attribute_found = true;
                            break;
                        }
                    }
                } ?>
            <script type="text/javascript">var wpc_product_page=true;</script>
                <div id="wpc_data_default_configuration" class="panel woocommerce_options_panel wc-metaboxes-wrapper">
               <input type="button" id="wpc_refresh_button" value="<?=__('Refresh','wpc');?>" class="button button-primary button-large" />

                    <div class="options_group" id="wpc_option_data_default_configuration">
                        <h2><?php _e('Pre Configure Options', 'wpc'); ?></h2>
                        <?php if (!$variation_attribute_found) { ?>
                            <div class="inline woocommerce-message">
                                <p><?php _e('Before selecting variations, add and save some attributes on the <strong>Attributes</strong> tab.', 'wpc'); ?></p>
                            </div>
                        <?php
                        } else {
                            $c = 0;
                            $default_config = get_post_meta($post->ID, '_wpc_default_config', true);

                            foreach ($attributes as $attribute) {
                                if (!$attribute['is_variation']) {
                                    continue;
                                } ?>
                                <h3><?= esc_html(wc_attribute_label($attribute['name'])) ?></h3>
                                <?php if ($attribute["is_taxonomy"]) {
                                    $check_variation = isset($default_config[$attribute['name']]) ? $default_config[$attribute['name']] : "";  ?>
                                    <table class="wpc_variation_price">
                                        <?php $post_terms = wp_get_post_terms($post->ID, $attribute['name']); ?>
                                        <?php foreach ($post_terms as $term) { ?>
                                            <tr>
                                                <td>
                                        <span
                                            class="wpc_variation_text_default"><strong><?= esc_html($term->name) ?></strong>
                                             </span></td>
                                                <td><input class="wpc_variation_checkbox_default" type="checkbox"
                                                           name="_wpc_default_config[<?= $attribute['name'] ?>]"
                                                           <?php if ($check_variation == $term->slug){ ?>checked="checked"<?php } ?>
                                                           value="<?= $term->slug; ?>"/></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                <?php } ?>
                            <?php } ?>

                        <?php
                        }
                        $c++; ?>

                    </div>
                    <div class="options_group">
                        <h2><?php _e('Base Step') ?></h2>
                        <table>
                            <?php
                            $check_depenedency = get_post_meta($post->ID, '_wpc_color_dependency', true);
                            if(is_array($attributes) && !empty($attributes)){
                            foreach ($attributes as $attribute) {
                                if (!$attribute['is_variation']) {
                                    continue;
                                } ?>
                                <tr>
                                    <td>
                                        <?= esc_html(wc_attribute_label($attribute['name'])) ?>
                                    </td>
                                    <td>
                                        <?php $checked = $attribute['name'] == $check_depenedency ? 'checked' : ''; ?>
                                        <input type="radio" <?= $checked; ?> value="<?= $attribute['name'] ?>"
                                               name="_wpc_color_dependency">
                                    </td>
                                </tr>

                            <?php }} ?>
                        </table>
                    </div>
                    <div class="options_group">
                        <h2><?php _e('Base Color Step','wpc') ?></h2>
                        <table>
                            <?php
                            $check_depenedency = get_post_meta($post->ID, '_wpc_base_color_dependency', true);
                            if(is_array($attributes) && !empty($attributes)){
                                foreach ($attributes as $attribute) {
                                    if (!$attribute['is_variation']) {
                                        continue;
                                    } ?>
                                    <tr>
                                        <td>
                                            <?= esc_html(wc_attribute_label($attribute['name'])) ?>
                                        </td>
                                        <td>
                                            <?php $checked = $attribute['name'] == $check_depenedency ? 'checked' : ''; ?>
                                            <input type="radio" <?= $checked; ?> value="<?= $attribute['name'] ?>"
                                                   name="_wpc_base_color_dependency">
                                        </td>
                                    </tr>

                                <?php }} ?>
                        </table>
                    </div>
                    <div class="options_group">
                        <h2><?php _e('Exclude From Image') ?></h2>
                        <table>
                            <?php
                            $check_depenedency = get_post_meta($post->ID, '_wpc_exclude_image', true) && get_post_meta($post->ID, '_wpc_exclude_image', true) != "" ? get_post_meta($post->ID, '_wpc_exclude_image', true) : array();
                if(is_array($attributes) && !empty($attributes)){
                            foreach ($attributes as $attribute) {
                                if (!$attribute['is_variation']) {
                                    continue;
                                } ?>
                                <tr>
                                    <td>
                                        <?= esc_html(wc_attribute_label($attribute['name'])) ?>
                                    </td>
                                    <td>
                                        <?php $checked = in_array($attribute['name'], $check_depenedency) ? 'checked' : ''; ?>
                                        <input type="checkbox" <?= $checked; ?> value="<?= $attribute['name'] ?>"
                                               name="_wpc_exclude_image[]">
                                    </td>
                                </tr>

                            <?php }} ?>
                        </table>
                    </div>
                    <div class="options_group">
                        <h2><?php _e('Exclude From Color') ?></h2>
                        <table>
                            <?php
                            //  $check_depenedency=get_post_meta($post->ID, '_wpc_exclude_color', true);
                            $check_depenedency = get_post_meta($post->ID, '_wpc_exclude_color', true) && get_post_meta($post->ID, '_wpc_exclude_color', true) != "" ? get_post_meta($post->ID, '_wpc_exclude_color', true) : array();
                if(is_array($attributes) && !empty($attributes)){
                            foreach ($attributes as $attribute) {
                                if (!$attribute['is_variation']) {
                                    continue;
                                } ?>
                                <tr>
                                    <td>
                                        <?= esc_html(wc_attribute_label($attribute['name'])) ?>
                                    </td>
                                    <td>
                                        <?php $checked = in_array($attribute['name'], $check_depenedency) ? 'checked' : ''; ?>
                                        <input type="checkbox" <?= $checked; ?> value="<?= $attribute['name'] ?>"
                                               name="_wpc_exclude_color[]">
                                    </td>
                                </tr>

                            <?php }} ?>
                        </table>
                    </div>
                    <div class="options_group wpc_hidden">
                        <h2><?php _e('Embroidery Step') ?></h2>
                        <table>
                            <?php
                            $check_dependency = get_post_meta($post->ID, '_wpc_embroidery_step', true);
                if(is_array($attributes) && !empty($attributes)){
                            foreach ($attributes as $attribute) {
                                if (!$attribute['is_variation']) {
                                    continue;
                                } ?>
                                <tr>
                                    <td>
                                        <?= esc_html(wc_attribute_label($attribute['name'])) ?>
                                    </td>
                                    <td>
                                        <?php $checked = $attribute['name'] == $check_dependency ? 'checked' : ''; ?>
                                        <input type="radio" <?= $checked; ?> value="<?= $attribute['name'] ?>"
                                               name="_wpc_embroidery_step">
                                    </td>
                                </tr>

                            <?php }} ?>
                        </table>
                    </div>
                    <div class="options_group wpc_hidden">
                        <h2><?php _e('Embroidery Term') ?></h2>

                        <p>
                            <select name="_wpc_embroidery_term">
                                <?php
                                $check_dependency = get_post_meta($post->ID, '_wpc_embroidery_term', true);
                                foreach ($attributes as $attribute) {
                                    if (!$attribute['is_variation']) {
                                        continue;
                                    } ?>
                                    <option value="">---</option>
                                    <optgroup
                                        label="<?= esc_html(wc_attribute_label($attribute['name'])) ?>"></optgroup>

                                    <?php if ($attribute["is_taxonomy"]) {
                                        $post_terms = wp_get_post_terms($post->ID, $attribute['name']);
                                        ?>
                                        <?php foreach ($post_terms as $term) {
                                            $check_value = $attribute['name'] . "|" . $term->slug;
                                            $selected = $check_dependency == $check_value ? "selected" : "";
                                            ?>
                                            <option <?= $selected ?>
                                                value="<?php echo $attribute['name'] . "|" . $term->slug ?>"><?= esc_html($term->name) ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </p>
                    </div>
                    <div class="options_group">
                        <h2><?php _e('Texture Terms','wpc') ?></h2>
                        <p>
                            <select name="_wpc_texture_term[]" multiple style="width: 100%">
                                <?php
                                $check_dependency = get_post_meta($post->ID, '_wpc_texture_term', true)?get_post_meta($post->ID, '_wpc_texture_term', true):array();
                                foreach ($attributes as $attribute) {
                                    if (!$attribute['is_variation']) {
                                        continue;
                                    } ?>
                                    <optgroup label="<?= esc_html(wc_attribute_label($attribute['name'])) ?>"></optgroup>
                                    <?php if ($attribute["is_taxonomy"]) {
                                        $post_terms = wp_get_post_terms($post->ID, $attribute['name']);
                                        ?>
                                        <?php foreach ($post_terms as $term) {
                                            $check_value = $attribute['name'] . "|" . $term->slug;
                                            $selected =in_array($check_value,$check_dependency)  ? "selected" : "";
                                            ?>
                                            <option <?= $selected ?>
                                                value="<?php echo $attribute['name'] . "|" . $term->slug ?>"><?= esc_html($term->name) ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </p>
                    </div>

                    <?php  if(!empty($base_images)) : $bs = get_post_meta($post->ID, '_wpc_selected_base', true);
                        ?>
                    <div class="options_group">
                        <h2><?php _e('Base Image Category','wpc') ?></h2>
                        <p>
                            <select name="wpc_selected_base">
                                <option value="">---</option>
                                <?php foreach ($base_images as $base) :
                                    ?>
                                    <option <?php if($base->term_id == $bs) echo 'selected="selected"'; ?> value="<?=$base->term_id?>"><?=$base->name ?></option>
                                <?php endforeach ?>
                            </select>
                        </p>
                    </div>
                    <?php endif ?>
                    <div class="options_group">
                        <?php if (isset($_GET['post'])) { ?>
                            <a target="_blank" class="button button-default"
                               href="options.php?page=wpc_configurator_image&post=<?= $_GET['post'] ?>"><?php _e('Images Configuration', 'wpc'); ?></a>
                            <a target="_blank" class="button button-default"
                               href="options.php?page=wpc_configurator_color&post=<?= $_GET['post'] ?>"><?php _e('Color Configuration', 'wpc'); ?></a>
                            <a target="_blank" class="button button-default"
                               href="options.php?page=wpc_configurator_texture&post=<?= $_GET['post'] ?>"><?php _e('Multicolor Cord Configuration', 'wpc'); ?></a>
                            <a target="_blank" class="button button-default" href="options.php?page=wpc_configurator_embroidery&post=<?= $_GET['post'] ?>"><?php _e('Embroidery Configuration', 'wpc'); ?></a>
                            <a target="_blank" class="button button-default"
                               href="options.php?page=wpc_configurator_images&post=<?=$_GET["post"]?>"><?=__('Images','wpc')?></a>
                        <?php } ?>
                    </div>
                </div>
            <div id="wpc_instructions_tab" class="panel woocommerce_options_panel wc-metaboxes-wrapper">
                <input type="button" id="wpc_refresh_instructions_button" value="<?=__('Refresh','wpc');?>" class="button button-primary button-large" />
                <div id="wpc_instructions_config">
                <table cellpadding="10" cellspacing="25">

                    <?php $wpc_instructions=get_post_meta($post->ID,'_wpc_instructions',true); if ($attributes) {foreach ($attributes as $attribute) { ?>
                    <tr>
                      <td><?php _e(wc_attribute_label($attribute['name'])) ?></td>
                      <td>
                       <textarea cols="50" rows="3" name="wpc_instructions[<?=$attribute['name']?>]"><?=@$wpc_instructions[$attribute['name']]?></textarea>
                      </td>
                    </tr>

                    <?php }?>
                        <tr>
                            <td>
                                <?=__('Finish Step','wpc')?>
                            </td>
                            <td>
                                <textarea cols="50" rows="3" name="wpc_instructions[wpc_finish]"><?=@$wpc_instructions['wpc_finish']?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?=__('General Instruction','wpc')?>
                            </td>
                            <td>
                                <textarea cols="50" rows="3" name="wpc_instructions[wpc_general]"><?=@$wpc_instructions['wpc_general']?></textarea>
                            </td>
                        </tr>
                   <?php }?>
                </table>
                </div>
            </div>
            <?php
           // }
        }
    }

    new WPC_Admin_Product();
}

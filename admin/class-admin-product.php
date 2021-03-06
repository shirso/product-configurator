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
            add_action('woocommerce_product_option_terms', array(&$this, 'product_option_terms'), 10, 2);
        }
        public function product_option_terms($tax, $i){
            global $woocommerce, $thepostid;
            if( in_array( $tax->attribute_type, array( 'image') ) ) {
                $attribute_taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
                ?>
                <select multiple="multiple" data-placeholder="<?php _e( 'Select terms', 'wpb' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo $i; ?>][]">
                    <?php
                    $all_terms = get_terms( $attribute_taxonomy_name, 'orderby=name&hide_empty=0' );
                    if ( $all_terms ) {
                        foreach ( $all_terms as $term ) {
                            $has_term = has_term( (int) $term->term_id, $attribute_taxonomy_name, $thepostid ) ? 1 : 0;
                            echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( $has_term, 1, false ) . '>' . $term->name . '</option>';
                        }
                    }
                    ?>
                </select>
                <button class="button plus select_all_attributes"><?php _e( 'Select all', 'wpb' ); ?></button> <button class="button minus select_no_attributes"><?php _e( 'Select none', 'wpb' ); ?></button>
                <button class="button fr plus add_new_attribute" data-attribute="<?php echo $attribute_taxonomy_name; ?>"><?php _e( 'Add new', 'wpb' ); ?></button>
                <?php
            }
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
            $post_id=$post->ID;
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
                   <div id="wpc_data_default_configuration_panel">
                    <div id="wpc_all_layers" class="wc-metabox">
                    <table>

                        <tr>
                            <td><?=__("Model Layer","wpc")?></td>
                            <td>
                                <select name="wpc_color_dependency" id="wpc_color_dependency">
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
                            </td>
                        </tr>
                        <tr>
                            <td><?=__("Base layer")?></td>
                            <td>
                                <select name="wpc_base_color_dependency" id="wpc_base_color_dependency">
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
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?=__('Static Image Layer','wpc')?>
                            </td>
                            <td>
                                <select name="wpc_static_layers[]" class="wpc_multiselect" multiple>

                                    <?php $wpc_static_layers=get_post_meta($post_id,'_wpc_static_layers',true);
                                    if (isset($attributes) && !empty($attributes)){
                                        foreach ($attributes as $attr) {
                                            if (!$attr['is_variation']) {
                                                continue;
                                            }
                                            $checked=is_array($wpc_static_layers) && in_array($attr['name'],$wpc_static_layers) ? 'selected' : "";
                                            ?>
                                            <option <?=$checked?> value="<?=$attr['name'];?>"><?= esc_html(wc_attribute_label($attr['name'])) ?></option>
                                        <?php }} ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?=__('Embroidery Step','wpc')?>
                            </td>
                            <td>
                                <select name="wpc_emb_layer" id="wpc_emb_layer">
                                    <option value="">---</option>
                                    <?php $check_emb = get_post_meta($post_id, '_wpc_emb_layer', true); if (isset($attributes) && !empty($attributes)){ foreach ($attributes as $attr) {
                                        if (!$attr['is_variation']) {
                                            continue;
                                        }
                                        ?>
                                        <?php $checked = $attr['name'] == $check_emb ? 'selected' : ''; ?>
                                        <option <?=$checked;?> value="<?= $attr['name'] ?>"> <?= esc_html(wc_attribute_label($attr['name'])) ?></option>
                                    <?php }}?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?=__("Cord Layers","wpc")?>
                            </td>
                            <td>
                                <select name="wpc_cord_layers[]" class="wpc_multiselect" multiple>

                                    <?php $wpc_cord_layers=get_post_meta($post_id,'_wpc_cord_layers',true);
                                    if (isset($attributes) && !empty($attributes)){
                                    foreach ($attributes as $attr) {
                                    if (!$attr['is_variation']) {
                                        continue;
                                    }
                                    $checked=is_array($wpc_cord_layers) && in_array($attr['name'],$wpc_cord_layers) ? 'selected' : "";
                                    ?>
                                        <option <?=$checked?> value="<?=$attr['name'];?>"><?= esc_html(wc_attribute_label($attr['name'])) ?></option>
                                    <?php }} ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" style="text-align: center">
                                <button id="wpc_btn_save_layers" class="button button-primary"><?=__("Save Layers","wpc")?></button>
                            </td>
                        </tr>
                    </table>
                    </div>
                    <div id="wpc_all_cords" class="wc-metabox">
                        <?php
                        $all_cords=get_post_meta($post_id,"_wpc_cord_layers",true);
                        $all_static_layers=get_post_meta($post_id,"_wpc_static_layers",true);?>
                        <table>
                            <?php  $model_layer = get_post_meta($post_id, '_wpc_color_dependency', true); $available_models=get_post_meta($post_id,'_wpc_available_models',true); if(!empty($model_layer)){ ?>
                          <tr>
                              <td>
                                  <?=__("Available Models","wpc")?>
                              </td>
                              <td>
                                  <?php

                                      $all_model_terms=get_terms($model_layer);
                                  ?>
                                  <select multiple class="wpc_multiselect" id="wpc_available_models" name="wpc_available_models[]">
                                      <?php if(!empty($all_model_terms)){
                                      foreach($all_model_terms as $term){
                                      if (has_term(absint($term->term_id), $model_layer, $post_id)) {
                                          $selectedModels=is_array($available_models) && in_array($term->term_id,$available_models) ? "selected" :"";
                                          ?>
                                          <option <?=$selectedModels?> value="<?=$term->term_id;?>"><?=$term->name;?></option>
                                      <?php }
                                      }}?>
                                  </select>
                                <span class="description"><?=__("Images available for these models","wpc")?></span>
                              </td>
                          </tr>
                            <?php }?>
                            <?php if(!empty($all_cords) || !empty($all_static_layers)){?>
                        <tr>
                            <td><?=__("No Cords or Static Layer","wpc")?></td>
                            <td>
                                <select multiple class="wpc_multiselect" id="wpc_no_cord" name="wpc_no_cords[]">
                                    <?php $no_cords=get_post_meta($post_id,"_wpc_no_cords",true);?>
                                    <?php if(!empty($all_cords)){?>
                                        <?php foreach($all_cords as $cord){?>
                                            <optgroup label="<?=wc_attribute_label($cord)?>">
                                                <?php $variations=get_terms($cord);
                                                if(!empty($variations)){
                                                    foreach($variations as $variation){
                                                        if (has_term(absint($variation->term_id), $cord, $post_id)) {
                                                            $selected_value=in_array($variation->term_id,$no_cords)?"selected":"";
                                                            ?>
                                                            <option <?=$selected_value?> value="<?=$variation->term_id;?>"><?=$variation->name;?></option>
                                                        <?php }}}?>

                                            </optgroup>
                                        <?php }?>
                                    <?php }?>
                                    <?php if(!empty($all_static_layers)){ ?>
                                    <?php foreach($all_static_layers as $layer){?>
                                    <optgroup label="<?=wc_attribute_label($layer)?>">
                                        <?php $variations=get_terms($layer);
                                        if(!empty($variations)){
                                            foreach($variations as $variation){
                                                if (has_term(absint($variation->term_id), $layer, $post_id)) {
                                                    $selected_value=in_array($variation->term_id,$no_cords)?"selected":"";
                                                    ?>
                                                    <option <?=$selected_value?> value="<?=$variation->term_id;?>"><?=$variation->name;?></option>
                                                <?php }}}?>
                                        <?php }?>
                                        <?php }?>
                                </select>
                            </td>
                        </tr>
                            <?php }?>
                            <?php $emb_layer=get_post_meta($post_id,"_wpc_emb_layer",true); if(!empty($emb_layer)){?>
                            <tr>
                                 <td><?=__("No Embroidery","wpc")?></td>
                                <td>
                                    <select id="wpc_no_emb" name="wpc_no_emb">
                                        <option value="">---</option>
                                        <?php
                                        $no_emb=get_post_meta($post_id,"_wpc_no_emb",true);
                                        $all_emb_terms=get_terms($emb_layer);
                                        if(!empty($all_emb_terms)){
                                        foreach($all_emb_terms as $variation){
                                            if (has_term(absint($variation->term_id), $emb_layer, $post_id)) {
                                                $selected_value=$variation->term_id==$no_emb?"selected":"";
                                        ?>
                                                <option <?=$selected_value?> value="<?=$variation->term_id;?>"><?=$variation->name;?></option>
                                        <?php }}}?>
                                    </select>
                                </td>
                            </tr>
                            <?php }?>
                            <?php if(!empty($all_cords) || !empty($all_static_layers)){?>
                            <tr>
                                <td><?=__("Multi-Color Cords","wpc")?></td>
                                <td>
                                    <select multiple class="wpc_multiselect" id="wpc_multicolor_cord" name="wpc_multicolor_cords[]">
                                        <?php
                                        $multicolor_cords=get_post_meta($post_id,"_wpc_multicolor_cords",true);
                                      if(!empty($all_cords)){foreach($all_cords as $cord){?>
                                            <optgroup label="<?=wc_attribute_label($cord)?>">
                                                <?php $variations=get_terms($cord);
                                                if(!empty($variations)){
                                                    foreach($variations as $variation){
                                                        if (has_term(absint($variation->term_id), $cord, $post_id)) {
                                                            $selected_value=in_array($variation->term_id,$multicolor_cords)?"selected":"";
                                                            ?>
                                                            <option <?=$selected_value?> value="<?=$variation->term_id;?>"><?=$variation->name;?></option>
                                                        <?php }}} ?>

                                            </optgroup>
                                        <?php }}?>
                                        <?php if(!empty($all_static_layers)){
                                            foreach($all_static_layers as $static_cord){
                                        ?>
                                        <optgroup label="<?=wc_attribute_label($static_cord)?>">
                                            <?php $static_variations=get_terms($static_cord);
                                            if(!empty($static_variations)){
                                                foreach($static_variations as $variation){
                                                    if (has_term(absint($variation->term_id), $static_cord, $post_id)) {
                                                        $selected_value=in_array($variation->term_id,$multicolor_cords)?"selected":"";
                                                        ?>
                                                        <option <?=$selected_value?> value="<?=$variation->term_id;?>"><?=$variation->name;?></option>
                                                    <?php }}} ?>
                                            </optgroup>
                                        <?php }}?>
                                    </select>
                                </td>
                            </tr>
                            <?php }?>
                            <tr>
                                <td colspan="2" style="text-align: center">
                                    <button id="wpc_btn_save_cords" class="button button-primary"><?=__("Save Cords","wpc")?></button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="wpc_buttons" class="wc-metabox">
                            <?php
                            if(!empty($model_layer) && !empty($available_models)){
                                $all_model_terms=get_terms($model_layer);
                            ?>
                                <table>
                                    <?php if(!empty($all_cords) || !empty($all_static_layers)){?>
                                    <tr>
                                        <td><?=__("Images & Colors for","wpc")?></td>
                                    <td>
                                   <?php if(!empty($all_model_terms)){
                                       foreach($all_model_terms as $term){
                                           if (has_term(absint($term->term_id), $model_layer, $post_id)) {
                                       ?>
                                        <a href="options.php?page=wpc_configurator_images&post=<?=$_GET["post"]?>&taxonomy=<?=$model_layer?>&term=<?=$term->term_id?>" target="_blank" class="button"><?=$term->name;?></a>
                                    <?php }}}?>
                                    </td>
                                    </tr>
                                    <?php }?>
                                    <?php if(!empty($emb_layer)){?>
                                    <tr>
                                        <td><?=__("Embroidery Options for","wpc")?></td>
                                        <td>
                                            <?php if(!empty($all_model_terms)){
                                                foreach($all_model_terms as $term){
                                                    if (has_term(absint($term->term_id), $model_layer, $post_id)) {
                                                        ?>
                                                        <a href="options.php?page=wpc_configurator_embroidery&post=<?=$_GET["post"]?>&taxonomy=<?=$model_layer?>&term=<?=$term->term_id?>" target="_blank" class="button"><?=$term->name;?></a>
                                                    <?php }}}?>
                                        </td>
                                    </tr>
                                <?php }?>
                                </table>
                        <?php }?>
                    </div>
                       <div id="wpc_others_options" class="wc-metabox">
                           <table>
                           <tr>
                               <td><?=__("Design Category")?></td>
                               <td>
                                   <?php $terms = get_terms( 'wpc_design_category');
                                   $selected_design=get_post_meta($post_id,"_wpc_selected_base",true);
                                   ?>
                                   <select name="wpc_selected_base">
                                       <option value="">---</option>
                                       <?php if(!empty($terms)){foreach($terms as $term){ $selected=$selected_design==$term->term_id?"selected":"";?>
                                           <option <?=$selected;?> value="<?=$term->term_id;?>"><?=$term->name;?></option>
                                       <?php }} ?>
                                   </select>
                               </td>
                           </tr>
                           <tr>
                               <td><?=__("Additional Comment")?></td>
                               <?php $additional_comment=get_post_meta($post_id,"_wpc_additional_comment",true);
                               $checked=$additional_comment==1?"checked":"";
                               ?>
                               <td><input type="checkbox" <?=$checked;?> name="wpc_additional_comment" value="1"></td>
                           </tr>
                               <tr>
                                   <td colspan="2" style="text-align: center">
                                       <button id="wpc_btn_save_others" class="button button-primary"><?=__("Save Others","wpc")?></button>
                                   </td>
                               </tr>
                           </table>
                       </div>
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

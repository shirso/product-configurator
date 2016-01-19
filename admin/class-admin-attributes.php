<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if( !class_exists('WPC_Admin_Attributes') ) {
    class WPC_Admin_Attributes {
        public function __construct() {
            $attributes = wc_get_attribute_taxonomy_names();
           // print_r($attributes);
            foreach( $attributes as $attribute ) {
                add_action( $attribute.'_add_form_fields', array( &$this, 'add_attr_type_to_add_form'), 10, 2 );
                add_action( $attribute.'_edit_form_fields', array( &$this, 'add_image_uploader_to_edit_form'), 10, 1);
                add_action( 'edited_'.$attribute, array( &$this, 'save_taxonomy_custom_meta'), 10, 2 );
                add_action( 'create_'.$attribute, array( &$this, 'save_taxonomy_custom_meta'), 10, 2 );
                add_action( 'delete_'.$attribute, array( &$this, 'delete_taxonomy_custom_meta'), 10, 2 );
                add_filter('manage_edit-' .$attribute . '_columns', array(&$this, 'woocommerce_product_attribute_columns'));
                add_filter('manage_' .$attribute . '_custom_column', array(&$this, 'woocommerce_product_attribute_column'), 10, 3);
            }
        }
        public function add_attr_type_to_add_form(){
            ?>
            <div class="form-field">
                <label for="hd_wpc_attribute_type"><?php _e('Attribute Type', 'wpc') ?></label>
                <div class="wpc-upload-field">
                    <select class="wide-fat" id="hd_wpc_attribute_type" name="hd_wpc_attribute_type">
                        <option data-value="wpc_none">None</option>
                        <option data-value="wpc_image">Image</option>
                        <option data-value="wpc_color">Color</option>
                    </select>
                </div>
            </div>
            <script type="text/javascript">var wpc_attribute_page=true;</script>
            <div class="form-field wpc-hidden wpc_image">
                <label for="hd_wpc_attribute_image"><?php _e('Image', 'wpc') ?></label>
                <div class="wpc-upload-field">
                    <input type="text" class="wide-fat" id="hd_wpc_attribute_image" value="" name="hd_wpc_attribute_image"/>
                </div>
                <button class="button button-secondary" id="btn_wpc_attribute_image_upload"><?php _e('Upload', 'wpc') ?></button>
            </div>
            <div class="form-field wpc-hidden wpc_color">
                <label for="hd_wpc_attribute_color"><?php _e('Color', 'wpc') ?></label>
                <div class="wpc-upload-field">
                    <input type="color" class="wide-fat" id="hd_wpc_attribute_color" value="" name="hd_wpc_attribute_color"/>
                </div>
            </div>
        <?php
        }
        public function add_image_uploader_to_edit_form($term){
            $term_id=$term->term_id;
            $img_or_color = get_option( '_wpc_variation_attr_'.$term_id );
            $attr_type = get_option( '_wpc_variation_attr_type_'.$term_id );
            ?>
            <tr class="form-field">
                <th>
                    <label for="hd_wpc_attribute_type"><?php _e('Attribute Type', 'wpc') ?></label>
                </th>
                <td class="wpc-upload-field">
                    <select class="wide-fat" id="hd_wpc_attribute_type" name="hd_wpc_attribute_type">
                        <option data-value="wpc_none">None</option>
                        <option <?php echo $attr_type=='Image'?'selected':''; ?> data-value="wpc_image">Image</option>
                        <option <?php echo $attr_type=='Color'?'selected':''; ?> data-value="wpc_color">Color</option>
                    </select>
                </td>
            </tr>
            <script type="text/javascript">var wpc_attribute_page=true;</script>
            <tr class="form-field wpc-hidden wpc_image" style="display: <?php echo $attr_type=='Image'?'block':'none'; ?>">
                <th>
                    <label for="hd_wpc_attribute_image"><?php _e('Image', 'wpc') ?></label>
                </th>
                <td class="wpc-upload-field">
                    <input type="text" class="wide-fat" id="hd_wpc_attribute_image" value="<?php echo $attr_type=='Image'?$img_or_color:''; ?>" name="hd_wpc_attribute_image"/>
                    <button class="button button-secondary" id="btn_wpc_attribute_image_upload"><?php _e('Upload', 'wpc') ?></button>
                </td>
            </tr>
            <tr class="form-field wpc-hidden wpc_color" style="display: <?php echo $attr_type=='Color'?'block':'none'; ?>">
                <th>
                <label for="hd_wpc_attribute_color"><?php _e('Color', 'wpc') ?></label>
                </th>
                <td class="wpc-upload-field">
                    <input type="color" class="wide-fat" id="hd_wpc_attribute_color" value="<?php echo $attr_type=='Color'?$img_or_color:''; ?>" name="hd_wpc_attribute_color"/>
                </td>
            </tr>
        <?php
        }
        public function save_taxonomy_custom_meta( $term_id ) {
            if($_POST['hd_wpc_attribute_type']=='Image'){
                if ( isset( $_POST['hd_wpc_attribute_image'] ) ){
                    update_option( '_wpc_variation_attr_'.$term_id, $_POST['hd_wpc_attribute_image'] );
                    update_option( '_wpc_variation_attr_type_'.$term_id, $_POST['hd_wpc_attribute_type'] );
                }
            }
            if($_POST['hd_wpc_attribute_type']=='Color'){
                if ( isset( $_POST['hd_wpc_attribute_color'] ) ){
                    update_option( '_wpc_variation_attr_'.$term_id, $_POST['hd_wpc_attribute_color'] );
                    update_option( '_wpc_variation_attr_type_'.$term_id, $_POST['hd_wpc_attribute_type'] );
                }
            }

        }
        public function delete_taxonomy_custom_meta($term_id){
            delete_option( '_wpc_variation_attr_'.$term_id );
            delete_option( '_wpc_variation_attr_type_'.$term_id );
        }
        public function woocommerce_product_attribute_columns($columns){
            $columns['wpc_img_or_color']=__('Image or Color', 'wpc');
            return $columns;
        }
        public function woocommerce_product_attribute_column($columns, $column, $id){
            if ($column == 'wpc_img_or_color') {
                $img_or_color=get_option('_wpc_variation_attr_'.$id);
                $attr_type=get_option('_wpc_variation_attr_type_'.$id);
                if($attr_type=='Image') {
                    echo '<img src="' . $img_or_color . '" style="height:40px"/>';
                }
                if($attr_type=='Color') {
                    echo '<input type="color" value="'.$img_or_color.'" disabled />';
                }
            }

        }
    }
    new WPC_Admin_Attributes();
}
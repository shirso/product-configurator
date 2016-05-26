<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if( !class_exists('WPC_Admin_Attributes') ) {
    class WPC_Admin_Attributes {
        public function __construct() {
            add_filter('product_attributes_type_selector',array(&$this,'product_attributes_type_selector'),20);
            $attributes=function_exists('wc_get_attribute_taxonomy_names')?wc_get_attribute_taxonomy_names():array();
            if(!empty($attributes)){
                foreach($attributes as $attribute){
                    add_action( $attribute.'_add_form_fields', array( &$this, 'add_attr_type_to_add_form'), 10, 2 );
                    add_action( $attribute.'_edit_form_fields', array( &$this, 'add_image_uploader_to_edit_form'), 10, 1);
                    add_action( 'edited_'.$attribute, array( &$this, 'save_taxonomy_custom_meta'), 10, 2 );
                    add_action( 'create_'.$attribute, array( &$this, 'save_taxonomy_custom_meta'), 10, 2 );
                    add_action( 'delete_'.$attribute, array( &$this, 'delete_taxonomy_custom_meta'), 10, 2 );
                    add_filter('manage_edit-' .$attribute . '_columns', array(&$this, 'woocommerce_product_attribute_columns'));
                    add_filter('manage_' .$attribute . '_custom_column', array(&$this, 'woocommerce_product_attribute_column'), 10, 3);
                }
            }
        }
        public  function product_attributes_type_selector($types){
            $types_custom= array(
                'image'=>__('Image','wpc')
            );
            $full_types=array_merge($types,$types_custom);
            return $full_types;
        }
        public function add_attr_type_to_add_form(){
            $taxonomy_name=esc_html($_GET["taxonomy"]);
            $attribute_type=get_variation_attribute_type($taxonomy_name);
            ?>
            <?php if($attribute_type== "image"){?>
                <div class="form-field">
                    <label for="hd_wpb_attribute_image"><?php _e('Image', 'wpc') ?></label>
                    <input type="text" class="wide-fat" id="hd_wpb_attribute_image" value="" name="hd_wpb_attribute_image"/>
                    <button class="button button-secondary wpc_upload_button"  id="btn_wpb_attribute_image_upload"><?php _e('Upload', 'wpc') ?></button>
                </div>
            <?php }?>
            <?php
        }
        public function add_image_uploader_to_edit_form($term){
            $term_id=$term->term_id;
            $attr_image= get_option( '_wpc_variation_attr_image_'.$term_id );
            $taxonomy_name=esc_html($_GET["taxonomy"]);
            $attribute_type=get_variation_attribute_type($taxonomy_name);
            ?>
            <?php if($attribute_type== "image"){?>
                <tr class="form-field">
                    <th>
                        <label for="hd_wpc_attribute_image"><?php _e('Image', 'wpc') ?></label>
                    </th>
                    <td class="wpc-upload-field">
                        <input type="text" class="wide-fat" id="hd_wpb_attribute_image" value="<?php echo $attr_image; ?>" name="hd_wpb_attribute_image"/>
                        <button class="button button-secondary wpc_upload_button" id="btn_wpc_attribute_image_upload"><?php _e('Upload', 'wpc') ?></button>
                    </td>
                </tr>
            <?php } ?>
            <?php
        }
        public function save_taxonomy_custom_meta( $term_id ) {
            if ( isset( $_POST['hd_wpb_attribute_image'] ) ){
                update_option( '_wpc_variation_attr_image_'.$term_id, $_POST['hd_wpb_attribute_image'] );
            }
        }
        public function delete_taxonomy_custom_meta($term_id){
            delete_option( '_wpc_variation_attr_image_'.$term_id );
        }
        public function woocommerce_product_attribute_columns($columns){
            $columns['wpc_attr_image']=__('Thumbnail', 'wpb');
            return $columns;
        }
        public function woocommerce_product_attribute_column($columns, $column, $id){
            if ($column == 'wpc_attr_image') {
                $attr_image=get_option('_wpc_variation_attr_image_'.$id);
                if(!empty($attr_image)){
                    echo '<img src="' . $attr_image . '" style="height:40px"/>';}
            }
        }
    }
    new WPC_Admin_Attributes();
}
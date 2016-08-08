<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly
if (!class_exists('WPC_Admin_metabox')) {
    class WPC_Admin_metabox
    {
        public function __construct()
        {
            add_meta_box('wpc_meta_id', __('Base Design', 'wpc'), array(&$this, 'wpc_add_metaboxes'), 'wpc_base_design', 'normal', 'high');
            add_meta_box('wpc_texture_image', __('Texture Image', 'wpc'), array(&$this, 'wpc_add_metaboxes_texture'), 'wpc_textures', 'normal', 'high');
            add_meta_box('wpc_color_code', __('Color Code', 'wpc'), array(&$this, 'wpc_add_metaboxes_color'), 'wpc_colors', 'normal', 'high');
            add_action('save_post',array(&$this,'wpc_save_base_saddle'));
        }
        public function wpc_add_metaboxes_texture(){
            global $post;
            $txture_image=get_post_meta($post->ID,'_wpc_texture_image',true);
            ?>
            <div class="form-field">
                <label for="hd_wpb_attribute_image"><?php _e('Image', 'wpc') ?></label>
                <input type="text" class="wide-fat" id="hd_wpb_attribute_image" value="<?=@$txture_image?>" name="hd_wpc_texture_image"/>
                <button class="button button-secondary wpc_upload_button"  id="btn_wpb_attribute_image_upload"><?php _e('Upload', 'wpc') ?></button>
            </div>
       <?php }
       public function wpc_add_metaboxes_color(){
           global $post;
           $color_code=get_post_meta($post->ID,'_wpc_color_code',true);
        ?>
           <input type="color" value="<?=@$color_code?>" name="hd_wpc_color_code" class="full-width wpc_color_picker1">
       <?php }
        public function wpc_add_metaboxes()
        {
            global $post;
            $designs=get_post_meta($post->ID,'_wpc_admin_design',true);
            $designs=isset($designs)?$designs:'';
            $check=empty($designs)?false:true;
            $base=$check?'disabled':'';
            ?>
            <div id="wpc_base_design_metabox">
                <script>
                    var wpc_base_design_page = true;
                    var edit_mode = '<?=$check?>';
                </script>
                <div id="wpc_base_design_stage" style="width: 600px">
                    <canvas></canvas>
                </div>
                <div class="wpc_metabox_buttons">
                    <input type="hidden" name="wpc_design[wpc_hidden_base_design]" id="wpc_hidden_base_design" value="<?=@$designs['wpc_hidden_base_design']?>" class="wpc_hidden" />
                    <input type="hidden" name="wpc_design[wpc_hidden_saddle_design]" value="<?=@$designs['wpc_hidden_saddle_design']?>" id="wpc_hidden_saddle_design" class="wpc_hidden" />
                    <button id="base_design_upload_btn" <?=$base?> type="button" class="button button-primary"><?=_e('Upload Base Design','wpc')?></button>
                    <button id="saddle_design_upload_btn" disabled type="button" class="button button-primary"><?=_e('Upload Secondary Design','wpc')?></button>
                    <button id="design_clear" type="button" class="button button-primary"><?=_e('Clear All','wpc')?></button>
                </div>
                <div class="wpc_metabox_values">
                    <table>
                        <tr>
                          <th><?=__('Scale','wpc')?></th>
                            <td><input type="text" name="wpc_design[wpc_saddle_scale]" value="<?=@$designs['wpc_saddle_scale']?>" id="saddle_scale"  /></td>
                        </tr>
                        <tr>
                        <th><?=__('ScaleX','wpc')?></th>
                        <td><input type="text" name="wpc_design[wpc_saddle_scaleX]" value="<?=@$designs['wpc_saddle_scaleX']?>" id="saddle_scaleX" /></td>
                        </tr>
                        <tr>
                            <th><?=__('ScaleY','wpc')?></th>
                            <td> <input type="text" name="wpc_design[wpc_saddle_scaleY]" value="<?=@$designs['wpc_saddle_scaleY']?>" id="saddle_scaleY"  /></td>
                        </tr>
                        <tr>
                            <th><?=__('Left','wpc')?></th>
                            <td> <input type="text" name="wpc_design[wpc_saddle_pos_x]" value="<?=@$designs['wpc_saddle_pos_x']?>" id="saddle_pos_x" /></td>
                        </tr>
                        <tr>
                            <th><?=__('Top','wpc')?></th>
                            <td> <input type="text" name="wpc_design[wpc_saddle_pos_y]" value="<?=@$designs['wpc_saddle_pos_y']?>" id="saddle_pos_y"  /></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
        }
        public function wpc_save_base_saddle($post_id){
            if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
            if(isset($_POST['wpc_design'])){
                update_post_meta( $post_id, '_wpc_admin_design', $_POST['wpc_design'] );
            }
            if(isset($_POST["hd_wpc_texture_image"])){
                update_post_meta( $post_id, '_wpc_texture_image', $_POST['hd_wpc_texture_image'] );
            }
            if(isset($_POST["hd_wpc_color_code"])){
                update_post_meta( $post_id, '_wpc_color_code', $_POST['hd_wpc_color_code'] );
            }
        }
    }

    new WPC_Admin_metabox();
}
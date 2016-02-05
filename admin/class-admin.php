<?php
if ( ! defined( 'ABSPATH' ) ) exit;
if( !class_exists('WPC_Admin') ) {
    class WPC_Admin{
        public function __construct() {
            add_action('admin_enqueue_scripts',array(&$this,'admin_scripts'));
            add_action('admin_menu',array(&$this, 'wpc_plugin_setup_menu'));
            add_action('admin_init',array(&$this,'plugin_admin_init'));
            add_action('admin_menu',array(&$this,'register_custom_submenu_page_image'));
            add_action('admin_menu',array(&$this,'register_custom_submenu_page_color'));
            add_action('admin_menu',array(&$this,'register_custom_submenu_page_texture'));
            add_action('admin_menu',array(&$this,'register_custom_submenu_page_embroidery'));
            add_action('admin_menu',array(&$this,'register_custom_submenu_page_images'));
            add_action('wp_ajax_wpc_save_configuration_form',array(&$this,'wpc_save_configuration_form'));
            add_action('wp_ajax_wpc_save_configuration_form_texture',array(&$this,'wpc_save_configuration_form_texture'));
            add_action('wp_ajax_wpc_save_configuration_form_color',array(&$this,'wpc_save_configuration_form_color'));
            add_action('wp_ajax_wpc_save_configuration_form_embroidery',array(&$this,'wpc_save_configuration_form_embroidery'));
            add_action('wp_ajax_wpc_save_tab_data',array(&$this,'wpc_save_tab_data'));
            add_action('wp_ajax_wpc_load_tab_data',array(&$this,'wpc_load_tab_data'));
        }
        public function admin_scripts(){
            wp_register_script('wpc_sheepit_jquery',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/jquery.sheepItPlugin-1.1.1.min.js','',false,true);
            wp_enqueue_script('wpc_sheepit_jquery');
            wp_enqueue_media();
            wp_register_script('wpc_chosen_jquery',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/chosen.jquery.js','',false,true);
            wp_register_script('wpc_blockUI',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/jquery.blockUI.min.js','',false,true);
            wp_enqueue_script('wpc_fabric',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/fabric.js','',false,true);
            wp_enqueue_script('wpc_steps',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/jquery.steps.min.js','',false,true);
            wp_enqueue_script('wpc_multiselect',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/jquery.multiselect.min.js','',false,true);
            wp_enqueue_script( 'wpc_chosen_jquery' );
            wp_enqueue_script( 'wpc_blockUI' );
            wp_register_script('wpc_admin_script',WPC_PLUGIN_ABSOLUTE_PATH.'admin/js/wpc.admin.js','',false,true);
            wp_localize_script('wpc_admin_script','wpc_image_labels',array('previous'=>__('Previous','wpc'),'next'=>__('Save and Continue','wpc'),'finish'=>'Save'));
            wp_enqueue_script('wpc_admin_script');
            wp_enqueue_style('wpc_grid12',WPC_PLUGIN_ABSOLUTE_PATH.'admin/css/grid12.css');
            wp_enqueue_style('wpc_chosen_style',WPC_PLUGIN_ABSOLUTE_PATH.'admin/css/chosen.min.css');
            wp_enqueue_style('wpc_steps_style',WPC_PLUGIN_ABSOLUTE_PATH.'admin/css/jquery.steps.css');
            wp_enqueue_style('wpc_ui_style','http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/ui-darkness/jquery-ui.css');
            wp_enqueue_style('wpc_multiselect_style',WPC_PLUGIN_ABSOLUTE_PATH.'admin/css/jquery.multiselect.css');
            wp_enqueue_style('wpc_admin_style',WPC_PLUGIN_ABSOLUTE_PATH.'admin/css/wpc.admin.css');
        }
        public  function wpc_save_configuration_form_texture(){
            parse_str($_POST['formData'],$params);
            update_post_meta(intval($_POST['postId']),'_wpc_texture_config',$params);
        }
        public function wpc_plugin_setup_menu(){
            add_options_page( 'WP Product Configurator Settings', 'WP Product Configurator', 'manage_options', 'wpc-plugin', array(&$this,'wpc_settings_init'));
        }
        public function wpc_settings_init(){
            ?>
            <div class="wrap">
                <h2><?php _e('WP Product Configurator Settings','wpc'); ?></h2>

                <form action="options.php" method="post">
                    <?php settings_fields('wpc_plugin_options'); ?>
                    <?php do_settings_sections('wpc_plugin_fields'); ?>
                    <input name="Submit" class="button button-primary button-large" type="submit" value="<?php esc_attr_e('Save Changes','wpc'); ?>" />
                </form>
            </div>
        <?php }
        public function plugin_admin_init(){
            if( array_key_exists('woocommerce', $GLOBALS) == false) {
                return;
            }

            $role = get_role( 'administrator' );
            $role->add_cap(WPC_Product_Configurator::CAPABILITY);
            require_once(WPC_PLUGIN_ADMIN_DIR . '/class-admin-attributes.php' );
            require_once(WPC_PLUGIN_ADMIN_DIR . '/class-admin-product.php' );
            require_once(WPC_PLUGIN_ADMIN_DIR . '/class-admin-metabox.php' );
            register_setting( 'wpc_plugin_options', 'wpc_settings',array(&$this,'plugin_options_validate'));
            add_settings_section('wpc_plugin_main', '', array(&$this,'plugin_description'), 'wpc_plugin_fields');
            add_settings_field('plugin_text_string1', __('Available Colors','wpc'), array(&$this,'add_colors'),'wpc_plugin_fields','wpc_plugin_main');
            add_settings_field('plugin_text_string4', __('Available Textures','wpc'), array(&$this,'add_textures'),'wpc_plugin_fields','wpc_plugin_main');
            add_settings_field('plugin_text_string3', __('Choose Fonts','wpc'), array(&$this,'choose_fonts'),'wpc_plugin_fields','wpc_plugin_main');
            add_settings_field('plugin_text_string5', __('Font Sizes','wpc'), array(&$this,'font_sizes'),'wpc_plugin_fields','wpc_plugin_main');
            add_settings_field('plugin_text_string6', __('Embroidery Character Limit','wpc'), array(&$this,'emb_char_limit'),'wpc_plugin_fields','wpc_plugin_main');
        }
        public function plugin_description(){

        }
        public function add_colors(){
            $options = get_option('wpc_settings');
            //print_r($options["colors_data"]);
           $data_color=array(); if(!empty($options["colors_data"])){ foreach($options["colors_data"] as $color){
                array_push($data_color,array('wpc_#index#_color_name'=>$color['name'],'wpc_#index#_color_value'=>$color['value']));
            }} //print_r($data_color);?>
            <script type="text/javascript">
                var wpc_config_page=true;
                var inject_data=[];
               inject_data= <?php echo json_encode($data_color); ?>;
            </script>
            <div id="wpc_sheepItForm">
                <!-- Form template-->
                <div id="wpc_sheepItForm_template">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for=""><?php _e('Color Name','wpc'); ?></label>
                            <input type="text" class="full-width" name="wpc_settings[colors_data][#index#][name]" id="wpc_#index#_color_name">
                        </div>
                        <div class="col-sm-6">
                            <input type="color"  class="full-width wpc_color_picker1" name="wpc_settings[colors_data][#index#][value]" id="wpc_#index#_color_value">
                        </div>
                    </div>
                    </div>
                <!-- /Form template-->
                <!-- No forms template -->
                <div id="wpc_sheepItForm_noforms_template"><?php _e('No Color','wpc'); ?></div>
                <!-- /No forms template-->
                    <!-- Controls -->
                    <div id="wpc_sheepItForm_controls" class="row">
                        <div id="wpc_sheepItForm_add" class="col-sm-3"><a class="button button-primary"><span><?php _e('Add Color','wpc'); ?></span></a></div>
                        <div id="wpc_sheepItForm_remove_last" class="col-sm-3"><a class="button button-primary"><span><?php _e('Remove','wpc'); ?> </span></a></div>
                        <div id="wpc_sheepItForm_remove_all"><a><span><?php _e('Remove all','wpc'); ?></span></a></div>
                        <div id="wpc_sheepItForm_add_n" class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-2"><input id="wpc_sheepItForm_add_n_input" type="text" size="4" /></div>
                            <div class="col-sm-10" id="wpc_sheepItForm_add_n_button"><a class="button button-primary"><span><?php _e('Add','wpc'); ?> </span></a></div></div>
                             </div>
                    </div>
                    <!-- /Controls -->

                </div>

<?php
        }
        public function add_textures(){
            $options = get_option('wpc_settings');
            $data_texture=array(); if(!empty($options["texture_data"])){ foreach($options["texture_data"] as $color){

                array_push($data_texture,array('wpc_#index#_texture_name'=>$color['name'],'wpc_#index#_texture_value'=>$color['value']));
            }}

            ?>
            <script type="text/javascript">
                var wpc_config_page=true;
                var inject_data_texture=[];
                inject_data_texture= <?php echo json_encode($data_texture); ?>;
            </script>
            <div id="wpc_sheepItForm1">
                <!-- Form template-->
                <div id="wpc_sheepItForm1_template">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for=""><?php _e('Texture Name','wpc'); ?></label>
                            <input type="text" class="full-width" name="wpc_settings[texture_data][#index#][name]" id="wpc_#index#_texture_name">
                        </div>
                        <div class="col-sm-6">
                            <input type="text"  class="full-width" name="wpc_settings[texture_data][#index#][value]" id="wpc_#index#_texture_value">
                            <button class="button wpc_texture_upload"><?=__('Upload','wpc')?></button>

                        </div>
                    </div>
                </div>
                <!-- /Form template-->
                <!-- No forms template -->
                <div id="wpc_sheepItForm1_noforms_template"><?php _e('No Texture','wpc'); ?></div>
                <!-- /No forms template-->
                <!-- Controls -->
                <div id="wpc_sheepItForm1_controls" class="row">
                    <div id="wpc_sheepItForm1_add" class="col-sm-3"><a class="button button-primary"><span><?php _e('Add Texture','wpc'); ?></span></a></div>
                    <div id="wpc_sheepItForm1_remove_last" class="col-sm-3"><a class="button button-primary"><span><?php _e('Remove','wpc'); ?> </span></a></div>
                    <div id="wpc_sheepItForm1_remove_all"><a><span><?php _e('Remove all','wpc'); ?></span></a></div>
                    <div id="wpc_sheepItForm1_add_n" class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-2"><input id="wpc_sheepItForm1_add_n_input" type="text" size="4" /></div>
                            <div class="col-sm-10" id="wpc_sheepItForm1_add_n_button"><a class="button button-primary"><span><?php _e('Add','wpc'); ?> </span></a></div></div>
                    </div>
                </div>
                <!-- /Controls -->

            </div>
          <?php
        }
        public function font_sizes(){
            $options = get_option('wpc_settings');
            $data_size=array(); if(!empty($options["font_size_data"])){ foreach($options["font_size_data"] as $size){

                array_push($data_size,array('wpc_#index#_size_name'=>$size['name'],'wpc_#index#_size_value'=>$size['value']));
            }}

            ?>
            <script type="text/javascript">
                var wpc_config_page=true;
                var inject_data_size=[];
                inject_data_size= <?php echo json_encode($data_size); ?>;
            </script>
            <div id="wpc_sheepItForm2">
                <!-- Form template-->
                <div id="wpc_sheepItForm2_template">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for=""><?php _e('Display Name','wpc'); ?></label>
                            <input type="text" class="full-width" name="wpc_settings[font_size_data][#index#][name]" id="wpc_#index#_size_name">
                        </div>
                        <div class="col-sm-6">
                            <label for=""><?php _e('Size','wpc'); ?></label>
                            <input type="text" size="2" class="" name="wpc_settings[font_size_data][#index#][value]" id="wpc_#index#_size_value">

                        </div>
                    </div>
                </div>
                <!-- /Form template-->
                <!-- No forms template -->
                <div id="wpc_sheepItForm2_noforms_template"><?php _e('No Font Size','wpc'); ?></div>
                <!-- /No forms template-->
                <!-- Controls -->
                <div id="wpc_sheepItForm2_controls" class="row">
                    <div id="wpc_sheepItForm2_add" class="col-sm-3"><a class="button button-primary"><span><?php _e('Add Size','wpc'); ?></span></a></div>
                    <div id="wpc_sheepItForm2_remove_last" class="col-sm-3"><a class="button button-primary"><span><?php _e('Remove','wpc'); ?> </span></a></div>
                    <div id="wpc_sheepItForm2_remove_all"><a><span><?php _e('Remove all','wpc'); ?></span></a></div>
                    <div id="wpc_sheepItForm2_add_n" class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-2"><input id="wpc_sheepItForm2_add_n_input" type="text" size="4" /></div>
                            <div class="col-sm-10" id="wpc_sheepItForm2_add_n_button"><a class="button button-primary"><span><?php _e('Add','wpc'); ?> </span></a></div></div>
                    </div>
                </div>
                <!-- /Controls -->

            </div>
         <?php
        }
        function plugin_options_validate($input) {return $input;}
        public function pre_configured_product(){
            $options = get_option('wpc_settings');
            $checked=$options['pre_configured_product']==1?'checked':'';
            ?>
            <input type="checkbox" <?=$checked?> name="wpc_settings[pre_configured_product]" value="1" />
            <?php
        }
        public  function choose_fonts(){
            $optimised_google_webfonts = get_transient( 'wpc_google_webfonts' );
            if ( empty( $optimised_google_webfonts ) )	{
                $google_webfonts = unserialize(file_get_contents('http://phat-reaction.com/googlefonts.php?format=php'));
                $optimised_google_webfonts = array();
                foreach($google_webfonts as $google_webfont) {
                    $optimised_google_webfonts[$google_webfont['css-name']] = $google_webfont['font-name'];
                }
                //cache google webfonts for one week
                set_transient('wpc_google_webfonts', $optimised_google_webfonts, 0 );
            }
            $options = get_option('wpc_settings');
           // print_r($options);
            $fonts=!empty($options["google_fonts"])?$options["google_fonts"]:array();

          ?>
            <select style="width: 100%" class="wide-fat" name="wpc_settings[google_fonts][]" id="wpc_google_fonts" multiple>
                <option value=""></option>
                <?php foreach($optimised_google_webfonts as $k=>$v){?>
                    <?php $selected=in_array($k,$fonts)?'selected':'' ?>
                    <option <?=$selected;?> value="<?=$k?>"><?=$v?></option>
                <?php }?>
            </select>
<?php
        }
        public function emb_char_limit(){
            $options = get_option('wpc_settings');
            ?>
           <div class="col-sm-6">
            <label><?=__('Per Line Character Limit','wpc'); ?></label>
             <input type="text" size="2" value="<?=@$options['emb_settings']['character_limit']?>" name="wpc_settings[emb_settings][character_limit]">
           </div>
            <div class="col-sm-6">
                <label><?=__('Line Limit','wpc'); ?></label>
                <input type="text" value="<?=@$options['emb_settings']['line_limit']?>" size="2" name="wpc_settings[emb_settings][line_limit]">
            </div>
<?php
        }

        public function register_custom_submenu_page_image(){
            add_submenu_page(
               'options.php'
                , 'Image Configurator'
                , 'Image Configurator'
                , 'manage_options'
                , 'wpc_configurator_image'
                , array(&$this,'my_custom_submenu_page_callback_image')
            );
        }
        public function register_custom_submenu_page_color(){
            add_submenu_page(
                'options.php'
                , 'Color Configurator'
                , 'Color Configurator'
                , 'manage_options'
                , 'wpc_configurator_color'
                , array(&$this,'my_custom_submenu_page_callback_color')
            );
        }
        public function register_custom_submenu_page_texture(){
            add_submenu_page(
                'options.php'
                , 'Texture Configurator'
                , 'Texture Configurator'
                , 'manage_options'
                , 'wpc_configurator_texture'
                , array(&$this,'my_custom_submenu_page_callback_texture')
            );
        }
        public function register_custom_submenu_page_embroidery(){
            add_submenu_page(
                'options.php'
                , 'Embroidery Configurator'
                , 'Embroidery Configurator'
                , 'manage_options'
                , 'wpc_configurator_embroidery'
                , array(&$this,'my_custom_submenu_page_callback_embroidery')
            );
        }

        public  function register_custom_submenu_page_images(){
            add_submenu_page(
                'options.php'
                , 'Image Configurator'
                , 'Image Configurator'
                , 'manage_options'
                , 'wpc_configurator_images'
                , array(&$this,'my_custom_submenu_page_callback_images')
            );
        }
        public function my_custom_submenu_page_callback_images(){
            require_once(WPC_PLUGIN_ADMIN_DIR.'/all_images.php');
        }
        public function my_custom_submenu_page_callback_image(){
            require_once(WPC_PLUGIN_ADMIN_DIR.'/image_configurator.php');
        }
        public function my_custom_submenu_page_callback_color(){
            require_once(WPC_PLUGIN_ADMIN_DIR.'/color_configurator.php');
        }
        public function my_custom_submenu_page_callback_texture(){
            require_once(WPC_PLUGIN_ADMIN_DIR.'/texture_configurator.php');
        }
        public function my_custom_submenu_page_callback_embroidery(){
            require_once(WPC_PLUGIN_ADMIN_DIR.'/embroidery_configurator.php');
        }
        public function wpc_save_configuration_form(){
           parse_str($_POST['formData'],$params);
           update_post_meta(intval($_POST['postId']),'_wpc_image_config',$params);
        }
        public function wpc_save_configuration_form_color(){
            $formData=parse_str($_POST['formDataColor'],$params);
            update_post_meta(intval($_POST['postId']),'_wpc_color_config',$params);
        }
        public function wpc_save_configuration_form_embroidery(){
           parse_str($_POST['formData'],$params);
            update_post_meta(intval($_POST['postId']),'_wpc_emb_logo_size',$params['wpc_emb_logo_size']);
            update_post_meta(intval($_POST['postId']),'_wpc_emb_font_size',$params['wpc_emb_font_size']);
            update_post_meta(intval($_POST['postId']),'_wpc_emb_no_embroidery',$params['wpc_emb_no_embroidery']);
            update_post_meta(intval($_POST['postId']),'_wpc_emb_positions',$params['wpc_emb_positions']);
            update_post_meta(intval($_POST['postId']),'_wpc_emb_colors',$params['wpc_emb_colors']);
            exit;
        }
        public function wpc_save_tab_data(){
            $section=esc_html($_POST["section"]);
            parse_str($_POST['formData'],$params);
            $postId=absint($_POST["postId"]);
            switch ($section) {
                case 'wpc_base_edge' :
                    update_post_meta($postId,'_wpc_color_dependency',$params['_wpc_color_dependency']);
                    update_post_meta($postId,'_wpc_base_color_dependency',$params['_wpc_base_color_dependency']);
                    update_post_meta($postId,'_wpc_base_image_base',$params['wpc_base_image_base']);
                    update_post_meta($postId,'_wpc_base_image_texture',$params['wpc_base_image_texture']);
                    update_post_meta($postId,'_wpc_edge_image_base',$params['wpc_edge_image_base']);
                    update_post_meta($postId,'_wpc_edge_image_texture',$params['wpc_edge_image_texture']);
                    break;
                case 'wpc_cord_layers' :
                    update_post_meta($postId,'_wpc_cord_layers',$params['wpc_cord_layers']);
                    break;
                case 'wpc_multicolor_cords' :
                    update_post_meta($postId,'_wpc_multicolor_cords',$params['wpc_multicolor_cords']);
                    break;
                case 'wpc_cord_images' :
                    print_r($params['wpc_combination']);
                    break;
                case 'wpc_multicolor_images' :
                    break;
                default:
                    break;
            }
            exit;
        }
        public function wpc_load_tab_data(){
            $postId=absint($_POST["postId"]);
            $section=trim($_POST["section"]);
            if($section!="wpc_cord_layers"  || $section!="wpc_base_edge") {
                switch ($section) {
                    case "wpc_multicolor_cords":
                        require_once(WPC_PLUGIN_ADMIN_DIR.'/wpc_multicolor_cords.php');
                        break;
                    case "wpc_cord_images":
                        require_once(WPC_PLUGIN_ADMIN_DIR.'/wpc_cord_images.php');
                        break;
                    case "wpc_multicolor_images":
                        require_once(WPC_PLUGIN_ADMIN_DIR.'/wpc_multicolor_images.php');
                        break;
                    default:
                        echo 'success';
                }
            }
            exit;
        }

    }
    new WPC_Admin();
}
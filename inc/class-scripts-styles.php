<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
if (!class_exists('WPC_Scripts_Styles')) {
    class WPC_Scripts_Styles
    {
        public static $add_script = false;

        public function __construct()
        {
            add_action('wp_enqueue_scripts', array(&$this, 'enqueue_styles'));
            add_action('wp_footer', array(&$this, 'footer_handler'));

        }

        public function enqueue_styles()
        {
            global $post;
            if (wpc_enabled($post->ID)) {
//                wp_enqueue_style('wpc_style_grids', WPC_PLUGIN_ABSOLUTE_PATH . 'css/grid12.css', false);
//                wp_enqueue_style('wpc_style_tabs', WPC_PLUGIN_ABSOLUTE_PATH . 'css/responsive-tabs.css', false);
//                wp_enqueue_style('wpc_magnific_popup_style', WPC_PLUGIN_ABSOLUTE_PATH . 'css/magnific-popup.css', false);
//                wp_enqueue_style('wpc_cloud_zoom_style', WPC_PLUGIN_ABSOLUTE_PATH . 'cloud-zoom/cloudzoom.css', false);
//                wp_enqueue_style('wpc_style_frontend', WPC_PLUGIN_ABSOLUTE_PATH . 'css/wpc_style_frontend.css', false);
                wp_enqueue_style('wpc_style_frontend', WPC_PLUGIN_ABSOLUTE_PATH . 'css/wpc_frontend_style.min.css', false);
            }
        }

        public function footer_handler()
        {
            global $product,$woocommerce;;
            if (self::$add_script) {

                if(isset($_GET['wpc_cart_item_key'])){
                    $wpc_cart_item_key=trim($_GET['wpc_cart_item_key']);
                    $wpc_cart=$woocommerce->cart->get_cart();
                    $wpc_get_cart=$wpc_cart[$wpc_cart_item_key];
                    $wpc_cart_variations=$wpc_get_cart['variation'];
                    $wpc_extra_items=array();
                    if(isset($wpc_get_cart['wpc_original_item'])){
                        $wpc_extra_items=$wpc_get_cart['wpc_original_item'];
                        if(isset($wpc_extra_items['wpc_product_image_data'])){
                            unset($wpc_extra_items['wpc_product_image_data']);
                        }
                        $wpc_extra_items=array_filter($wpc_extra_items);
                    }
                    $wpc_cart_redirect_items=array('variations'=>$wpc_cart_variations,'extra_items'=>$wpc_extra_items);

                }
                ?>
                <div id="wpc_base_design_configur" class="wpc_hidden">
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
                    foreach($all_posts as $post){
                ?>
                        <div id="wpc_design_data_<?=$post->ID?>">
                            <?php $design_data=get_post_meta($post->ID,'_wpc_admin_design',true);?>
                            <div class="base_image"> <img src="<?= @$design_data['wpc_hidden_base_design'] ?>"/></div>
                            <div class="saddle_image"><img src="<?= @$design_data['wpc_hidden_saddle_design'] ?>"/></div>
                            <input type="hidden" class="wpc_scale" value="<?= @$design_data['wpc_saddle_scale'] ?>" />
                            <input type="hidden" class="wpc_scaleX" value="<?= @$design_data['wpc_saddle_scaleX'] ?>" />
                            <input type="hidden" class="wpc_scaleY" value="<?= @$design_data['wpc_saddle_scaleY'] ?>" />
                            <input type="hidden" class="wpc_left" value="<?= @$design_data['wpc_saddle_pos_x'] ?>" />
                            <input type="hidden" class="wpc_top" value="<?= @$design_data['wpc_saddle_pos_y'] ?>" />
                        </div>
                    <?php }}?>
                </div>
                <?php
                $google_webfonts = get_option('wpc_settings');
                if (!empty($google_webfonts['google_fonts'])) {
                    echo '<link href="http://fonts.googleapis.com/css?family=' . implode("|", $google_webfonts['google_fonts']) . '" rel="stylesheet" type="text/css">';
                }
                $emb_limits=$google_webfonts['emb_settings'];
                wp_register_script('wpc_frontend_script', WPC_PLUGIN_ABSOLUTE_PATH . 'assets/js/wpc_frontend_script.min.js', array('jquery'), false);
                wp_localize_script('wpc_frontend_script','wpc_cart_redirect_items',$wpc_cart_redirect_items);
                wp_localize_script('wpc_frontend_script','wpc_emb_limit',$emb_limits);
                wp_localize_script('wpc_frontend_script','wpc_emb_layer',get_post_meta($product->id,'_wpc_emb_layer',true));
                wp_localize_script('wpc_frontend_script', 'wpc_ajaxUrl', array('ajaxUrl' => admin_url('admin-ajax.php')));
                wp_localize_script('wpc_frontend_script','translate_text',array("image_file"=>__("Image File","wpc"),'reset_text'=>__("Are you sure to reset?","wpc"),'model_change'=>__("All selection will be lost. Are you sure to continue?","wpc"),'left'=>__('Left','wpc'),'right'=>__('Right','wpc'),'finish_all'=>__("Please Finish All Steps!!!","wpc"),'step_alert'=>__("You Have Missed Some Options.Please Check!!!","wpc")));
                wp_enqueue_script('underscore');
                wp_enqueue_script('wpc_frontend_plugins', WPC_PLUGIN_ABSOLUTE_PATH . 'assets/js/wpc_plugins.min.js', array('jquery'), false);
                wp_enqueue_script('wpc_cloud_zoom', WPC_PLUGIN_ABSOLUTE_PATH . 'cloud-zoom/cloudzoom.js', array('jquery'), false);
                wp_enqueue_script('wpc_frontend_script');
            }
        }


    }

    new WPC_Scripts_Styles();
}
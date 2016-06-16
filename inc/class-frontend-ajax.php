<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!class_exists('WPC_Frontend_Ajax')) {
    class WPC_Frontend_Ajax{
        public function __construct() {
            add_action( 'wp_ajax_wpc_embroidery_image_upload', array(&$this,'upload_image') );
            add_action( 'wp_ajax_nopriv_wpc_embroidery_image_upload', array(&$this,'upload_image'));
            add_action( 'wp_ajax_wpc_get_color_data', array(&$this,'wpc_get_color_data'));
            add_action( 'wp_ajax_nopriv_wpc_get_color_data', array(&$this,'wpc_get_color_data'));
            add_action( 'wp_ajax_wpc_get_texture_data', array(&$this,'wpc_get_texture_data'));
            add_action( 'wp_ajax_nopriv_wpc_get_texture_data', array(&$this,'wpc_get_texture_data'));
            add_action( 'wp_ajax_wpc_get_image_data', array(&$this,'wpc_get_image_data'));
            add_action( 'wp_ajax_nopriv_wpc_get_image_data', array(&$this,'wpc_get_image_data'));
            add_action( 'wp_ajax_wpc_get_emb_config', array(&$this,'wpc_get_emb_config'));
            add_action( 'wp_ajax_nopriv_wpc_get_emb_config', array(&$this,'wpc_get_emb_config'));
        }
        public function upload_image(){
            $defaultModel=absint($_POST["model"]);
            $productId=absint($_POST["productId"]);
            $emb_config=get_post_meta($productId,"_wpc_emb_config_".$defaultModel,true);
            $upload_dir = wp_upload_dir();
            $wpc_upload_dir=$upload_dir["basedir"]."/product_configurator_images/";
            $wpc_upload_path=$upload_dir["baseurl"]."/product_configurator_images/";
            $ext = strtolower(pathinfo($_FILES['wpc_image_upload']['name'], PATHINFO_EXTENSION));
            $newFileName = microtime(true).'.'.$ext;
            $source = $_FILES['wpc_image_upload']['tmp_name'];
            $dest = $wpc_upload_dir.$newFileName;
            move_uploaded_file($source,$dest);
            $returnData=array();
            $returnData["filepath"]=$wpc_upload_path.$newFileName;
            $returnData["positions"]=self::get_emb_positions($emb_config);
            $returnData["sizes"]=array("width"=>$emb_config["logo_width"],"height"=>$emb_config["logo_height"]);
            echo  json_encode($returnData);
            exit;
        }
        public function wpc_get_color_data(){
            $defaultModel=absint($_POST["model"]);
            $attribute=esc_attr($_POST["attribute"]);
            $term=esc_attr($_POST["term"]);
            $termId=absint($_POST["termId"]);
            $productId=absint($_POST["productId"]);
            $colorsMeta=get_post_meta($productId,"_wpc_colors_".$defaultModel,true);
            $colorOfThisAttribute=isset($colorsMeta[$attribute][$term]['colors'])?$colorsMeta[$attribute][$term]['colors']:array();
            $html="";
           if(!empty($colorOfThisAttribute)){
               foreach ($colorOfThisAttribute as $color) {
                   $all = explode('|', $color);
                   $html .= '<div class="flclr">';
                   $html.='<div class="change_color insec" data-color="'.$all[1].'" data-attribute="'.$attribute.'" data-term="'.$termId.'" data-display="'.$all[0].'" style="background: '.$all[1].'">';
                   $html .= '</div>';
                   $html.='   <p>'.$all[0].'</p>';
                   $html .= '</div>';
               }}
            echo $html;
            exit;
        }
        public function wpc_get_texture_data(){
            $defaultModel=absint($_POST["model"]);
            $attribute=esc_attr($_POST["attribute"]);
            $term=esc_attr($_POST["term"]);
            $productId=absint($_POST["productId"]);
            $texturesMeta=get_post_meta($productId,"_wpc_textures_".$defaultModel,true);
            $textureOfThisAttribute=isset($texturesMeta[$attribute][$term]['textures'])?$texturesMeta[$attribute][$term]['textures']:array();
//            print_r($textureOfThisAttribute);
            $html="";
            if(!empty($textureOfThisAttribute)){
                foreach ($textureOfThisAttribute as $texture) {
                    $all = explode('|', $texture);
                    $html .= '<div class="flclr">';
                    $html.='<div class="change_texture insec" style="background:url('.$all[1].')">';
                    $html .= '</div>';
                    $html.='   <p>'.$all[0].'</p>';
                    $html .= '</div>';
                }
            }
            echo $html;
            exit;
        }
        public function wpc_get_image_data(){
            $defaultModel=absint($_POST["model"]);
            $attribute=esc_attr($_POST["attribute"]);
            $productId=absint($_POST["productId"]);
            $cordsData=$_POST["cordsData"];
            $cordLayers=get_post_meta($productId,"_wpc_cord_layers",true);
            $filterData=array_map(create_function('$n', 'return null;'), array_flip($cordLayers));
           if(!empty($cordsData)){
               foreach($cordsData as $cord){
               if(array_key_exists($cord["attribute"],$filterData)){
                   $filterData[$cord["attribute"]]=$cord["term"];
                }}}
            $imageData=get_post_meta($productId,'_wpc_cord_images_'.$defaultModel,true);
            $combinations=$imageData["combinations"];
            $images=$imageData["images"];
            $key123=array_search($filterData,$combinations);
            $returnImage=array_key_exists($key123,$images) ? $images[$key123] : array();
            echo json_encode($returnImage);
            exit;
        }
        public function wpc_get_emb_config(){
            $defaultModel=absint($_POST["model"]);
            $productId=absint($_POST["productId"]);
            $type=esc_attr($_POST["type"]);
            $returnData=array();
            $emb_config=get_post_meta($productId,"_wpc_emb_config_".$defaultModel,true);
            $setting=get_option("wpc_settings");
            switch ($type){
                case "text":
                    $fontOptions="";
                    $fontOptions.='<option value="">'.__("---Fonts---","wpc").'</option>';
                    foreach ($setting['google_fonts'] as $gfont) {
                        $gname = str_replace('+', ' ', $gfont);
                        $fontOptions .= '<option value="' . $gname . '" style="font-family:' . $gname . '">' . $gname . '</option>';
                    }
                    $returnData["fontOptions"]=$fontOptions;
                    $fontSizes="";
                    $fontSizes.='<option value="">'.__("---Font Size---","wpc").'</option>';

                    if(!empty($setting["font_size_data"])){
                        foreach($setting["font_size_data"] as $size){
                            $selected=$emb_config["font_size"]==$size["value"]?"selected":"";
                            $fontSizes.='<option '.$selected.' value="'.$size["value"].'">'.$size["name"].'</option>';
                        }
                    }
                    $returnData["fontSizes"]=$fontSizes;
                    $colors="";
                    if(!empty($emb_config["colors"])){
                        foreach($emb_config["colors"] as $colorEmb){
                            $allEmb = explode('|', $colorEmb);
                            $colors.='<div class="flclr">';
                            $colors.='<div class="change_color_emb insec" style="background: '.$allEmb[1].'"';
                            $colors.='data-all="'. $allEmb[0] . '|' . $allEmb[1].'" data-colorname="'.$allEmb[0].'" data-color="'.$allEmb[1].'">';
                            $colors.='</div>';
                            $colors.='<p>'.$allEmb[0].'</p>';
                            $colors.='</div>';
                        }
                    }
                    $returnData["colors"]=$colors;
                    $returnData["positions"]=self::get_emb_positions($emb_config);
                    break;
                case "image":
                    break;
            }
            echo json_encode($returnData);
            exit;
        }
        public function get_emb_positions($emb_config){
            $positions=isset($emb_config["positions"]) && !empty($emb_config["positions"]) ? $emb_config["positions"] : array();
            $buttons="";
            if(!empty($positions)){
                foreach($positions as $position){
                    $default_button=isset($position['default'])?'active':'';
                    $buttons.='<button type="button" data-left="'.@$position['left'].'" data-top="'.@$position['top'].'" class="'.$default_button.' wpc_buttons wpc_emb_btn btn btn-default">'.@$position['name'].'</button>';
                }
            }
            return $buttons;
        }
    }
    new WPC_Frontend_Ajax();
}
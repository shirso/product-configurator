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
            add_action( 'wp_ajax_wpc_get_static_images', array(&$this,'wpc_get_static_images'));
            add_action( 'wp_ajax_nopriv_wpc_get_static_images', array(&$this,'wpc_get_static_images'));
            add_action( 'wp_ajax_wpc_get_single_static_image', array(&$this,'wpc_get_single_static_image'));
            add_action( 'wp_ajax_nopriv_wpc_get_single_static_image', array(&$this,'wpc_get_single_static_image'));
            add_action( 'wp_ajax_wpc_get_texture_image_data', array(&$this,'wpc_get_texture_image_data'));
            add_action( 'wp_ajax_nopriv_wpc_get_texture_image_data', array(&$this,'wpc_get_texture_image_data'));
            add_action( 'wp_ajax_wpc_get_single_texture_image_data', array(&$this,'wpc_get_single_texture_image_data'));
            add_action( 'wp_ajax_nopriv_wpc_get_single_texture_image_data', array(&$this,'wpc_get_single_texture_image_data'));
            add_action( 'wp_ajax_wpc_get_design_data', array(&$this,'wpc_get_design_data'));
            add_action( 'wp_ajax_nopriv_wpc_get_design_data', array(&$this,'wpc_get_design_data'));
            add_action( 'wp_ajax_wpc_post_final_image', array(&$this,'wpc_post_final_image'));
            add_action( 'wp_ajax_nopriv_wpc_post_final_image', array(&$this,'wpc_post_final_image'));
            add_action( 'wp_ajax_wpc_get_emb_button_data', array(&$this,'wpc_get_emb_button_data'));
            add_action( 'wp_ajax_nopriv_wpc_get_emb_button_data', array(&$this,'wpc_get_emb_button_data'));
        }
        public function wpc_get_emb_button_data(){
            $productId=absint($_POST["productId"]);
            $defaultModel=absint($_POST["model"]);
            $emb_config=get_post_meta($productId,"_wpc_emb_config_".$defaultModel,true);
            $emb_options=$emb_config["emb_options"];
            $html="";
            switch ($emb_options){
                case "both":
                    $html.='<li><a data-type="image" class="btab wpc_emb_tabs wpc_buttons" href="#wpc_emb_image">'.__('Image','wpc').'</a></li>';
                    $html.='<li><a data-type="text" class="btab wpc_emb_tabs wpc_buttons" href="#wpc_emb_text">'.__('Text','wpc').'</a></li>';
                    break;
                case "image":
                    $html.='<li><a data-type="image" class="btab wpc_emb_tabs wpc_buttons" href="#wpc_emb_image">'.__('Image','wpc').'</a></li>';
                    break;
                case "text":
                    $html.='<li><a data-type="text" class="btab wpc_emb_tabs wpc_buttons" href="#wpc_emb_text">'.__('Text','wpc').'</a></li>';
                    break;
            }
            $returnArray=array("html"=>$html,"type"=>$emb_options);
            echo json_encode($returnArray);
            exit;
        }
        public function wpc_get_single_static_image(){
            $defaultModel=absint($_POST["model"]);
            $productId=absint($_POST["productId"]);
            $attribute=esc_html($_POST["attribute"]);
            $static_images=get_post_meta($productId,"_wpc_static_images_".$defaultModel,true);
            $return_images=isset($static_images[$attribute])?$static_images[$attribute]:array();
            echo json_encode($return_images);exit;
        }
        public function wpc_get_static_images(){
            $defaultModel=absint($_POST["model"]);
            $productId=absint($_POST["productId"]);
            $static_images=get_post_meta($productId,"_wpc_static_images_".$defaultModel,true);
            $not_require=get_post_meta($productId,"_wpc_not_require_".$defaultModel,true);
            if(!empty($not_require)){
                foreach($not_require as $k=>$v){
                    if(isset($static_images[$k])){
                        unset ($static_images[$k]);
                    }
                }
            }
            echo json_encode($static_images);
            exit;
        }
        public function wpc_post_final_image(){
            $imageData=$_POST["imageData"];
//            list($type, $imageData) = explode(';', $imageData);
//            list(, $imageData)      = explode(',', $imageData);
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

            $upload_dir = wp_upload_dir();
            $wpc_upload_dir=$upload_dir["basedir"]."/product_configurator_images/final_design/";
            //$wpc_upload_path=$upload_dir["baseurl"]."/product_configurator_images/final_design/";
            $imageName=uniqid().".png";
            file_put_contents($wpc_upload_dir.$imageName, $imageData);
            echo $imageName;
            exit;
        }
        public function upload_image(){
            $defaultModel=absint($_POST["model"]);
            $productId=absint($_POST["productId"]);
            $emb_config=get_post_meta($productId,"_wpc_emb_config_".$defaultModel,true);
            $upload_dir = wp_upload_dir();
            $wpc_upload_dir=$upload_dir["basedir"]."/product_configurator_images/";
            $wpc_upload_path=$upload_dir["baseurl"]."/product_configurator_images/";
            $ext = strtolower(pathinfo($_FILES['wpc_image_upload']['name'], PATHINFO_EXTENSION));
            $newFileName = uniqid().'.'.$ext;
            $source = $_FILES['wpc_image_upload']['tmp_name'];
            list($width, $height) = getimagesize($source);
            $dest = $wpc_upload_dir.$newFileName;
            move_uploaded_file($source,$dest);
            $returnData=array();
            $returnData["filepath"]=$wpc_upload_path.$newFileName;
            $returnData["positions"]=self::get_emb_positions($emb_config);
            $returnData["sizes"]=array("width"=>$emb_config["logo_width"],"height"=>$emb_config["logo_height"]);
            $returnData["imageSize"]=array("width"=>$width,"height"=>$height);
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
            $butonType=esc_html($_POST["buttonType"]);
            $html="";
           if(!empty($colorOfThisAttribute)){
               foreach ($colorOfThisAttribute as $color) {
                   $colorId=absint($color);
                   if(acme_post_exists($colorId)) {
                       $colorDetails = get_post($colorId, ARRAY_A);
                       $color_code=get_post_meta($colorId,"_wpc_color_code",true);
//                   $all = explode('|', $color);
                   $html .= '<div class="flclr">';
                   $html .= '<div class="change_color ' . $butonType . ' insec" data-color="' . $color_code . '" data-attribute="' . $attribute . '" data-term="' . $termId . '" data-display="' .$colorDetails["post_title"] . '" style="background: ' . $color_code . '">';
                   $html .= '</div>';
                   $html .= '   <p>' .$colorDetails["post_title"] . '</p>';
                   $html .= '</div>';
                       }
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
            $butonType=esc_html($_POST["buttonType"]);
            $textureOfThisAttribute=isset($texturesMeta[$attribute][$term]['textures'])?$texturesMeta[$attribute][$term]['textures']:array();
            $html="";
            if(!empty($textureOfThisAttribute)){
                foreach ($textureOfThisAttribute as $texture) {
                  //  $all = explode('|', $texture);
                    $colorId=absint($texture);
                    if(acme_post_exists($colorId)) {
                        $colorDetails = get_post($colorId, ARRAY_A);
                        $color_code = get_post_meta($colorId, "_wpc_texture_image", true);
                        $html .= '<div class="flclr">';
                        $html .= '<div class="change_texture ' . $butonType . ' insec" data-attribute="' . $attribute . '" data-term="' . $term . '" data-display="' . $colorDetails["post_title"] . '" data-clean="' . $colorId . '" style="background:url(' . $color_code . ')">';
                        $html .= '</div>';
                        $html .= '   <p>' . $colorDetails["post_title"] . '</p>';
                        $html .= '</div>';
                    }
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
        public function wpc_get_texture_image_data(){
            $defaultModel=absint($_POST["model"]);
            $productId=absint($_POST["productId"]);
            $cordsData=$_POST["cordsData"];
            $textureData=$_POST["textureData"];
            $cordLayers=get_post_meta($productId,"_wpc_cord_layers",true);
            $filterData=array_map(create_function('$n', 'return null;'), array_flip($cordLayers));
            if(!empty($cordsData)){
                foreach($cordsData as $cord){
                    if(array_key_exists($cord["attribute"],$filterData)){
                        $filterData[$cord["attribute"]]=$cord["term"];
                    }}}
            $imageData=get_post_meta($productId,'_wpc_multicord_images_'.$defaultModel,true);
            $combinations=$imageData["combinations"];
            $images=$imageData["images"];
            $key123=array_search($filterData,$combinations);
            $returnImage=array_key_exists($key123,$images) ? $images[$key123] : array();
            $textureFilter=array();
            if(!empty($textureData)){foreach($textureData as $texture){
                $textureFilter[$texture["attribute"]]=$texture["texture"];
            }}
            $finalArray=array();
            if(!empty($textureFilter)){foreach($textureFilter as $k=>$t){
                if($t!=""){
                   // print_r($t);
                    $finalArray[$k]=isset($returnImage[$k][$t]) ? $returnImage[$k][$t] : "";
                }
            }}
            echo json_encode($finalArray);
            exit;
        }
        public function wpc_get_single_texture_image_data(){
            $defaultModel=absint($_POST["model"]);
            $productId=absint($_POST["productId"]);
            $attribute=esc_html($_POST["attribute"]);
            $texture=esc_html($_POST["texture"]);
            $imageData=get_post_meta($productId,'_wpc_multicord_images_'.$defaultModel,true);
            $imageData=isset($imageData["static_images"][$attribute][$texture])?$imageData["static_images"][$attribute][$texture]:array();
            echo json_encode($imageData);exit;
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
                    $availableFonts=$emb_config['available_fonts'];
                    $defaultFont=$emb_config["default_font"];
                   if(!empty($availableFonts)){ foreach ($availableFonts as $gfont) {
                        $gname = str_replace('+', ' ', $gfont);
                       $selectFont=$gfont==$defaultFont?"selected":"";
                        $fontOptions .= '<option '.$selectFont.' value="' . $gname . '" style="font-family:' . $gname . '">' . $gname . '</option>';
                    }}
                    $returnData["fontOptions"]=$fontOptions;
                    $fontSizes="";
                    $selected_font_size=0;
                    $fontSizes.='<option value="">'.__("---Font Size---","wpc").'</option>';
                    if(!empty($setting["font_size_data"])){
                        foreach($setting["font_size_data"] as $size){
                            $selected=$emb_config["font_size"]==$size["value"]?"selected":"";
                            if($emb_config["font_size"]== $size["value"]){
                                $selected_font_size= $size["value"];
                            }
                            $fontSizes.='<option '.$selected.' value="'.$size["value"].'">'.$size["name"].'</option>';
                        }
                    }
                    $returnData["fontSizes"]=$fontSizes;
                    $returnData["selectedFontSize"]=$selected_font_size;
                    $returnData["selectedFontStyle"]=$defaultFont;
                    $colors="";
                    if(!empty($emb_config["colors"])){
                        foreach($emb_config["colors"] as $colorEmb){
                            if(acme_post_exists($colorEmb)) {
                                $colorDetails = get_post($colorEmb, ARRAY_A);
                                $color_code = get_post_meta($colorEmb, "_wpc_color_code", true);
                                // $allEmb = explode('|', $colorEmb);
                                $colors .= '<div class="flclr">';
                                $colors .= '<div class="change_color_emb insec" style="background: ' . $color_code . '"';
                                $colors .= 'data-all="' . $colorDetails["post_title"] . '|' . $color_code . '" data-colorname="' . $colorDetails["post_title"] . '" data-color="' . $color_code . '">';
                                $colors .= '</div>';
                                $colors .= '<p>' . $colorDetails["post_title"] . '</p>';
                                $colors .= '</div>';
                            }
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
        public function wpc_get_design_data(){
            $postId=absint($_POST["postId"]);
            $designs=get_post_meta($postId,'_wpc_admin_design',true);
            echo json_encode($designs);
            exit;
        }
    }
    new WPC_Frontend_Ajax();
}
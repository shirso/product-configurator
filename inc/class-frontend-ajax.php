<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//include ('resize.php');
if(!class_exists('WPC_Frontend_Ajax')) {
    class WPC_Frontend_Ajax{
        public function __construct() {
           // add_action( 'wp_ajax_get_images', array(&$this,'get_all_variation_images') );
          //  add_action( 'wp_ajax_nopriv_get_images', array(&$this,'get_all_variation_images'));
            add_action( 'wp_ajax_wpc_embroidery_image_upload', array(&$this,'upload_image') );
            add_action( 'wp_ajax_nopriv_wpc_embroidery_image_upload', array(&$this,'upload_image'));
            add_action( 'wp_ajax_wpc_get_color_data', array(&$this,'wpc_get_color_data'));
            add_action( 'wp_ajax_nopriv_wpc_get_color_data', array(&$this,'wpc_get_color_data'));
            add_action( 'wp_ajax_wpc_get_texture_data', array(&$this,'wpc_get_texture_data'));
            add_action( 'wp_ajax_nopriv_wpc_get_texture_data', array(&$this,'wpc_get_texture_data'));
            add_action( 'wp_ajax_wpc_get_image_data', array(&$this,'wpc_get_image_data'));
            add_action( 'wp_ajax_nopriv_wpc_get_image_data', array(&$this,'wpc_get_image_data'));
        }
        public function upload_image(){
            $upload_dir = wp_upload_dir();
            $wpc_upload_dir=$upload_dir["basedir"]."/product_configurator_images/";
            $wpc_upload_path=$upload_dir["baseurl"]."/product_configurator_images/";
            $ext = strtolower(pathinfo($_FILES['wpc_image_upload']['name'], PATHINFO_EXTENSION));
            $newFileName = microtime(true).'.'.$ext;
            $source = $_FILES['wpc_image_upload']['tmp_name'];
            $dest = $wpc_upload_dir.$newFileName;
            move_uploaded_file($source,$dest);
           echo  json_encode(array('filepath'=>$wpc_upload_path.$newFileName));
            exit;

        }
        public function wpc_get_color_data(){
            $defaultModel=absint($_POST["model"]);
            $attribute=esc_attr($_POST["attribute"]);
            $term=esc_attr($_POST["term"]);
            $productId=absint($_POST["productId"]);
            $colorsMeta=get_post_meta($productId,"_wpc_colors_".$defaultModel,true);
            $colorOfThisAttribute=isset($colorsMeta[$attribute][$term]['colors'])?$colorsMeta[$attribute][$term]['colors']:array();
            $html="";
           if(!empty($colorOfThisAttribute)){
               foreach ($colorOfThisAttribute as $color) {
                   $all = explode('|', $color);
                   $html .= '<div class="flclr">';
                   $html.='<div class="change_color insec" data-color="'.$all[1].'" data-display="'.$all[0].'" style="background: '.$all[1].'">';
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
    }
    new WPC_Frontend_Ajax();
}
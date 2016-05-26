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
        }
        public  function upload_image(){
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
    }
    new WPC_Frontend_Ajax();
}
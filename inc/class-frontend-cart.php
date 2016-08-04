<?php
if (!defined('ABSPATH')) exit;
if (!class_exists('WPC_Cart')) {
    class WPC_Cart
    {
        public function __construct()
        {
           add_filter('woocommerce_add_cart_item_data', array(&$this, 'add_cart_item_data'), 10, 2);
            add_filter('woocommerce_get_cart_item_from_session', array(&$this, 'get_cart_item_from_session'), 10, 2);
            add_filter('woocommerce_get_item_data', array(&$this, 'get_item_data'), 10, 2);
            add_filter('woocommerce_add_cart_item', array(&$this, 'add_cart_item'), 10, 1);
            add_action('woocommerce_add_order_item_meta', array(&$this, 'order_item_meta'), 10, 2);
            add_filter( 'woocommerce_cart_item_thumbnail', array(&$this, 'change_cart_item_thumbnail'), 10, 2 );
            add_filter('woocommerce_hidden_order_itemmeta',array(&$this,'hide_thumbnail_data_order'),10,1);
            add_action( 'woocommerce_admin_order_item_headers', array( &$this, 'add_order_item_header' ) );
            add_action( 'woocommerce_admin_order_item_values', array( &$this, 'admin_order_item_values' ), 10, 3 );
            add_filter('woocommerce_order_items_meta_display',array(&$this,'woocommerce_order_items_meta_display'),10,2);
            add_filter('woocommerce_cart_item_name',array(&$this,'woocommerce_cart_item_name'),10,3);
            add_filter('woocommerce_order_item_thumbnail',array(&$this,'woocommerce_order_item_thumbnail'),10, 2);
            add_filter('add_to_cart_redirect', array(&$this, 'redirect_to_checkout'));
        }
        function redirect_to_checkout() {
            return WC()->cart->get_cart_url();
        }
        function woocommerce_order_item_thumbnail($html,$item){
            if(isset($item['wpc_product_image_data'])){
              $image_data=$item['wpc_product_image_data'];
                $upload_dir = wp_upload_dir();
                $wpc_upload_path=$upload_dir["baseurl"]."/product_configurator_images/final_design/";
                $html='<div style="margin-bottom: 5px"><img src="'.$wpc_upload_path.$image_data.'" alt="' . esc_attr__( 'Product Image', 'woocommerce' ) . '" width="100" height="" style="vertical-align:middle; margin-right: 10px;" /></div>';
            }
           return $html;
        }

          function woocommerce_cart_item_name($link, $cart_item, $cart_item_key){
            if(isset($cart_item['wpc_extra_cart_item'])){
                $url=add_query_arg(array('wpc_cart_item_key'=>$cart_item_key),$cart_item['data']->get_permalink());
                return sprintf( '<a href="%s">%s</a>', $url, $cart_item['data']->get_title() );
            }else{
                return $link;
            }
        }
        function add_cart_item_data($cart_item_meta, $product_id) {
            global $woocommerce;
            if (isset($cart_item_meta['wpc_extra_cart_item']) && isset($cart_item_meta['wpc_original_item']) ) {
                return $cart_item_meta;
            }
            if(isset($_POST["wpc_extra_item"])) {
                $cart_item_meta['wpc_extra_cart_item']=$_POST["wpc_extra_item"];
            }
            if(isset($_POST["wpc_original_item"])){
                $cart_item_meta['wpc_original_item']=$_POST["wpc_original_item"];
            }
            return $cart_item_meta;
        }
        function get_cart_item_from_session($cart_item, $values) {
            if (isset($values['wpc_extra_cart_item'])) {
                $cart_item['wpc_extra_cart_item'] = $values['wpc_extra_cart_item'];
            }
            return $cart_item;
        }
        function get_item_data($other_data, $cart_item) {
            if (isset($cart_item['wpc_extra_cart_item'])) {

                foreach($cart_item["wpc_extra_cart_item"] as $key=>$value){
                    if($value!=""){

                     if($key=='wpc_product_image_data') {
                         $other_data[] = array('name' => 'wpc_image_data', 'display' => $value, 'value' => '','hidden'=>true);

                     }
                        elseif($key=='wpc_product_emb_type'){
                            $other_data[] = array('name' => __('Embroidery Type','wpc'), 'display' => $value, 'value' => '','hidden'=>false);
                        }elseif($key=='wpc_product_emb_image'){
                            $other_data[] = array('name' => __('Embroidery Image','wpc'), 'display' => $value, 'value' => '','hidden'=>false);
                        }elseif($key=='wpc_product_emb_text'){
                            $other_data[] = array('name' => __('Embroidery Text','wpc'), 'display' => $value, 'value' => '','hidden'=>false);
                        }elseif($key=='wpc_product_emb_font'){
                            $other_data[] = array('name' => __('Font','wpc'), 'display' => $value, 'value' => '','hidden'=>false);

                        }elseif($key=='wpc_product_emb_font_size'){
                            $other_data[] = array('name' => __('Font Size','wpc'), 'display' => $value, 'value' => '','hidden'=>false);

                        }elseif($key=='wpc_product_emb_font_weight'){
                            $other_data[] = array('name' => __('Font Weight','wpc'), 'display' => $value, 'value' => '','hidden'=>false);

                        }elseif($key=='wpc_product_emb_font_style'){
                            $other_data[] = array('name' => __('Font Style','wpc'), 'display' => $value, 'value' => '','hidden'=>false);

                        }elseif($key=='wpc_product_emb_color'){
                            $other_data[] = array('name' => __('Embroidery Color','wpc'), 'display' => $value, 'value' => '','hidden'=>false);

                        }elseif($key=='wpc_product_emb_position'){
                            $other_data[] = array('name' => __('Embroidery Position','wpc'), 'display' => $value, 'value' => '','hidden'=>false);

                        }elseif($key=='wpc_product_emb_angle'){
                         $other_data[] = array('name' => __('Embroidery Angle','wpc'), 'display' => $value, 'value' => '','hidden'=>false);

                     }elseif($key=='wpc_product_extra_comment'){
                         $other_data[] = array('name' => __('Additional Comment','wpc'), 'display' => $value, 'value' => '','hidden'=>false);

                     }elseif($key=='wpc_product_additional_comment'){
                         $other_data[] = array('name' => __('Additional Instructions','wpc'), 'display' => $value, 'value' => '','hidden'=>false);

                     }
                     else{
                         $other_data[] = array('name' => wc_attribute_label($key), 'display' => $value, 'value' => '');
                     }
                    }
                }
            }
          return $other_data;
        }
        function add_cart_item($cart_item) {
            global $woocommerce;
            if (isset($cart_item['wpc_extra_cart_item']) ) {

            }
            return $cart_item;
        }
        public function change_cart_item_thumbnail( $thumbnail, $cart_item=null ) {
            if( !is_null($cart_item) && isset($cart_item['wpc_extra_cart_item']) && isset($cart_item['wpc_extra_cart_item']['wpc_product_image_data']) ) {

                $thumbnail_data=$cart_item['wpc_extra_cart_item']['wpc_product_image_data'];
                $upload_dir = wp_upload_dir();
                $wpc_upload_path=$upload_dir["baseurl"]."/product_configurator_images/final_design/";
                $output='<img src="'.$wpc_upload_path.$thumbnail_data.'"/>';

                return $output;
            }
            return $thumbnail;
        }
        public function order_item_meta($item_id, $cart_item){
            if ( function_exists( 'woocommerce_add_order_item_meta' ) ) {
                $wpc_extra_cart_item = $cart_item['wpc_extra_cart_item'];
                foreach($wpc_extra_cart_item as $k=>$v){
                        if($v!='' || $v!=null)
                        {
                        if($k=='wpc_product_emb_type'){
                            wc_add_order_item_meta($item_id, __('Embroidery Type','wpc'), $v);
                        }elseif($k=='wpc_product_emb_image'){
                            wc_add_order_item_meta($item_id, __('Embroidery Image','wpc'), $v);
                        }elseif($k=='wpc_product_emb_text'){
                            wc_add_order_item_meta($item_id, __('Embroidery Text','wpc'), $v);
                        }elseif($k=='wpc_product_emb_font'){
                            wc_add_order_item_meta($item_id, __('Font','wpc'), $v);

                        }elseif($k=='wpc_product_emb_font_size'){
                            wc_add_order_item_meta($item_id, __('Font Size','wpc'), $v);

                        }elseif($k=='wpc_product_emb_font_weight'){
                            wc_add_order_item_meta($item_id, __('Font Weight','wpc'), $v);

                        }elseif($k=='wpc_product_emb_font_style'){
                            wc_add_order_item_meta($item_id, __('Font Style','wpc'), $v);

                        }elseif($k=='wpc_product_emb_color'){
                            wc_add_order_item_meta($item_id, __('Embroidery Color','wpc'), $v);

                        }elseif($k=='wpc_product_emb_position'){
                            wc_add_order_item_meta($item_id, __('Embroidery Position','wpc'), $v);

                        }elseif($k=='wpc_product_emb_angle'){
                            wc_add_order_item_meta($item_id, __('Embroidery Angle','wpc'), $v);

                        }elseif($k=='wpc_product_extra_comment'){
                            wc_add_order_item_meta($item_id, __('Additional Comment','wpc'), $v);

                        }elseif($k=='wpc_product_additional_comment'){
                            wc_add_order_item_meta($item_id, __('Additional Instructions','wpc'), $v);

                        }else{
                            wc_add_order_item_meta($item_id, wc_attribute_label($k), $v);
                        }
                        }
                }
            }
        }
        public function hide_thumbnail_data_order($hide_elements=array()){
           array_push($hide_elements,'wpc_product_image_data');
            return $hide_elements;
        }
        public function add_order_item_header() {

            ?>
            <th class="wpc-product-header"><?php _e( 'WPC Product', 'wpc' ); ?></th>
            <?php

        }
        public function admin_order_item_values( $_product, $item, $item_id ) {
            if( is_object($_product) ) {
               // print_r($item);
                if(isset($item['wpc_product_image_data'])){
                    $upload_dir = wp_upload_dir();
                    $wpc_upload_path=$upload_dir["baseurl"]."/product_configurator_images/final_design/";
                    $imgSrc=$wpc_upload_path.$item['wpc_product_image_data'];
                  ?>
                 <td class="wpc_product_image_thumbnail">
                  <img src="<?=$imgSrc?>" width="300" height="300" alt="">
                 </td>
                <?php
                }else{
                    return;
                }
            }
        }
        public function woocommerce_order_items_meta_display($output,$a){
            $output=str_replace( 'wpc_product_emb_type',__('Embroidery Type','wpc'),$output);
            $output=str_replace( 'wpc_product_emb_image',__('Embroidery Image','wpc'),$output);
            $output=str_replace( 'wpc_product_emb_position',__('Embroidery Position','wpc'),$output);
            $output=str_replace( 'wpc_product_emb_text',__('Embroidery Text','wpc'),$output);
            $output=str_replace( 'wpc_product_emb_font',__('Font','wpc'),$output);
            $output=str_replace( 'wpc_product_emb_font_size',__('Font Size','wpc'),$output);
            $output=str_replace( 'wpc_product_emb_font_weight',__('Font Weight','wpc'),$output);
            $output=str_replace( 'wpc_product_emb_font_style',__('Font Style','wpc'),$output);
            $output=str_replace( 'wpc_product_emb_color',__('Embroidery Color','wpc'),$output);
            $output=str_replace( 'wpc_product_extra_comment',__('Additional Comment','wpc'),$output);
            $output=str_replace( 'wpc_product_additional_comment',__('Additional Instructions','wpc'),$output);
           if(strpos($output,'<dt')) {
               $output=str_replace('wpc_product_image_data:',__('Image:','wpc'),$output);
               $output = mb_convert_encoding($output, 'HTML-ENTITIES', "UTF-8");
               $dom = new DOMDocument;
               libxml_use_internal_errors(true);

               $dom->loadHTML($output, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
               $xpath = new DOMXPath($dom);
               libxml_clear_errors();
               $classname = 'variation-wpc_product_image_data';

               $results = $xpath->query("//*[@class='" . $classname . "']");
               if ($results->length > 0) {
                   foreach ($results as $s) {
                       if ($s->tagName == 'dd') {
                           $imgSrc = $s->nodeValue;
                           $upload_dir = wp_upload_dir();
                           $wpc_upload_path=$upload_dir["baseurl"]."/product_configurator_images/final_design/";
                           $imgSrc=$wpc_upload_path.trim($imgSrc);
                           $s->nodeValue = '';
                           $title = $dom->createElement('img');
                           $title->setAttribute('src', $imgSrc);
                           $title->setAttribute('width', "150");
                           $s->appendChild($title);
                           $output = $s->ownerDocument->saveHTML();

                       }
                   }
               }
           }else{
               $output=str_replace('wpc_product_image_data:','',$output);
                if(isset($a->meta['wpc_product_image_data'])){
                    $imageData=$a->meta['wpc_product_image_data'];
                    $output=str_replace($imageData,'',$output);
                }
           }
           return $output;

        }
    }
    new WPC_Cart();
}
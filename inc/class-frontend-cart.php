<?php
if (!defined('ABSPATH')) exit;
if (!class_exists('WPC_Cart')) {
    class WPC_Cart
    {
        public function __construct()
        {   add_filter('woocommerce_add_cart_item_data', array(&$this, 'add_cart_item_data'), 10, 2);
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
        }
        function woocommerce_order_item_thumbnail($html,$item){

          /* if(isset($item['wpc_extra_cart_item'])){
               $image_data=$item['wpc_extra_cart_item']['wpc_product_image_data'];
               print_r($image_data);
           }*/
            if(isset($item['wpc_product_image_data'])){
              $image_data=$item['wpc_product_image_data'];
               // $image_data='data:image/png;base64,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';
                $html='<div style="margin-bottom: 5px"><img src="'.$image_data.'" alt="' . esc_attr__( 'Product Image', 'woocommerce' ) . '" width="100" height="" style="vertical-align:middle; margin-right: 10px;" /></div>';
             //   echo $html;
            }
           return $html;
        }

          function woocommerce_cart_item_name($link, $cart_item, $cart_item_key){
           // $url = add_query_arg( array('cart_item_key' => $cart_item_key), $cart_item['data']->get_permalink() );
            if(isset($cart_item['wpc_extra_cart_item'])){
                $url=add_query_arg(array('wpc_cart_item_key'=>$cart_item_key),$cart_item['data']->get_permalink());
                return sprintf( '<a href="%s">%s</a>', $url, $cart_item['data']->get_title() );
            }else{
                return $link;
            }
        }
        function add_cart_item_data($cart_item_meta, $product_id) {
            global $woocommerce;
            if (isset($cart_item_meta['wpc_extra_cart_item']) ) {
                return $cart_item_meta;
            }
            if(isset($_POST["wpc_extra_item"])) {
                $cart_item_meta['wpc_extra_cart_item']=$_POST["wpc_extra_item"];
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

                        }elseif($key=='wpc_product_extra_comment'){
                         $other_data[] = array('name' => __('Additional Comment','wpc'), 'display' => $value, 'value' => '','hidden'=>false);

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
                //print_r($thumbnail_data);
                $dom = new DOMDocument;
                libxml_use_internal_errors(true);
                $dom->loadHTML($thumbnail);
                $xpath = new DOMXPath( $dom );
                libxml_clear_errors();
                $doc = $dom->getElementsByTagName("img")->item(0);
                $src = $xpath->query(".//@src");
                foreach ( $src as $s ) {
                    $s->nodeValue = $thumbnail_data;
                }

                $output = $dom->saveXML( $doc );

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

                        }elseif($k=='wpc_product_extra_comment'){
                            wc_add_order_item_meta($item_id, __('Additional Comment','wpc'), $v);

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
                  ?>
                 <td class="wpc_product_image_thumbnail">
                  <img src="<?=$item['wpc_product_image_data']?>" width="300" height="300" alt="">
                 </td>
                <?php
                }else{
                    return;
                }
            }
        }
        public function woocommerce_order_items_meta_display($output,$a)
        {

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
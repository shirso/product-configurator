<?php
if (!defined('ABSPATH')) exit;
$post_id =absint($_GET['post']);
$term_id=absint($_GET["term"]);
$taxonomy=esc_html($_GET["taxonomy"]);
//$settings=get_option('wpc_settings');
//$allColors=$settings["colors_data"];
$emb_config=get_post_meta($post_id,"_wpc_emb_config_".$term_id,true);
$termDetails=get_term_by("id",$term_id,$taxonomy);
$all_available_models=get_post_meta($post_id,'_wpc_available_models',true);
$all_manufacturer = get_terms( 'wpc_color_manufacturer', array(
    'hide_empty' => true
));
$all_Colors=get_posts(array(
    'posts_per_page'=> -1,
    'post_type'=> 'wpc_colors',
    'post_status'=> 'publish',
    'orderby'=>'menu_order',
));
?>
<script type="text/javascript">
    var embroidery_config=true,
        termId=<?=$term_id?>;
</script>
<div align="center">
    <table>
        <tr>
            <td><?=__("Copy Embroidery Data from Another Model","wpc")?></td>
            <td>
                <select id="wpc_copy_model_select_emb">
                    <option value="">---</option>
                    <?php if(!empty($all_available_models)){ foreach($all_available_models as $k=>$model){?>
                        <?php if($model!=$term_id){
                            $modelDetails=get_term_by('id',$model,$taxonomy);
                            ?>
                            <option value="<?=$model?>"><?=$modelDetails->name;?></option>
                        <?php }?>
                    <?php }}?>
                </select>
            </td>
            <td>
                <button type="button" data-select="#wpc_copy_model_select_emb" data-postid="<?=$post_id?>" data-term="<?=$term_id?>" class="primary button" id="wpc_model_copy_emb"><?=__("Copy","wpc");?></button>
            </td>
        </tr>
    </table>
    <h1><?=__("Embroidery configuration for","wpc")?> "<?=$termDetails->name;?>"</h1>
<form action="" method="post" id="step_embroidery_form" data-id="<?=$post_id?>">
<table cellspacing="10" cellpadding="10">
    <tr>
        <th><?=__("Embroidery Options","wpc")?></th>
        <td>
            <select name="wpc_emb_config[emb_options]">
                <?php
                $emb_options=array("both"=>__("Both","wpc"),"image"=>__("Image","wpc"),"text"=>__("Text","wpc"));
                $emb_options_from_database=!empty($emb_config["emb_options"])?$emb_config["emb_options"]:"";
                ?>
                <?php foreach($emb_options as $k=>$v){?>
                    <option <?php if($emb_options_from_database==$k)echo "selected";?> value="<?=$k?>"><?=$v?></option>
                <?php }?>
            </select>
        </td>
    </tr>
</table>
<table cellspacing="10" cellpadding="5">
    <tr>
       <th><?=__('Default Logo Image Size. (Based on 800 X 800)','wpc')?></th>
        <td>
            <table>
                <tr>
                    <td><input type="text" name="wpc_emb_config[logo_width]" value="<?=@$emb_config['logo_width']?>" placeholder="<?=__('Width','wpc')?>" size="10"></td>
                    <td>X</td>
                    <td><input type="text" name="wpc_emb_config[logo_height]" value="<?=@$emb_config['logo_height']?>" placeholder="<?=__('Height','wpc')?>" size="10"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th><?=__("Available Fonts","wpc")?></th>
        <td>
            <select name="wpc_emb_config[available_fonts][]" multiple class="wpc_multiselect_chosen">
                <option value="">---</option>
                <?php
                $google_fonts=WPC_Admin::$optimised_google_webfonts;

                if(!empty($google_fonts)){
                    foreach($google_fonts as $font){
                        $select_fonts=!empty($emb_config["available_fonts"]) && in_array($font,$emb_config["available_fonts"]) ?"selected":"";
                    ?>
                   <option <?=$select_fonts?> value="<?=$font;?>"><?=$font;?></option>
                <?php }}?>
            </select>
        </td>
    </tr>
    <tr>
        <th><?=__("Default Font","wpc")?></th>
        <td>
            <select name="wpc_emb_config[default_font]" class="wpc_multiselect_chosen">
                <option value="">---</option>
              <?php  if(!empty($google_fonts)){
                foreach($google_fonts as $font){
                    $select_fonts=!empty($emb_config["default_font"]) && $emb_config["default_font"]==$font?"selected":"";
                ?>
                <option <?=$select_fonts?> value="<?=$font;?>"><?=$font;?></option>
                <?php }}?>
            </select>
        </td>
    </tr>
    <tr>
        <th><?=__('Default Font Size','wpc')?></th>
        <td>
            <select name="wpc_emb_config[font_size]">

                <option value="">---</option>
                <?php $options=get_option('wpc_settings'); $font_sizes=isset($options['font_size_data']) ? $options['font_size_data'] : array(); ?>
                <?php foreach($font_sizes as $size){  $selected=$emb_config['font_size']==@$size['value'] ?'selected':'' ?> ?>
                    <option <?=$selected;?> value="<?=@$size['value']?>"><?=@$size['name'];?></option>
                <?php }?>
            </select>
        </td>
    </tr>
</table>
<h4><?=__('Positions (Based on 800 X 800)','wpc');?></h4>
  <?php   $data_positions=array();
  if(!empty($emb_config['positions'])){ foreach($emb_config['positions'] as $position){
      array_push($data_positions,array('wpc_emb_positions_#index#_name'=>$position['name'],'wpc_emb_positions_#index#_left'=>$position['left'],'wpc_emb_positions_#index#_top'=>$position['top'],'wpc_emb_positions_#index#_default'=>@$position['default']));
    }}
  ?>
    <script type="text/javascript">
        var position_data=<?=json_encode($data_positions);?>
    </script>
    <div id="sheepItForm">
    <div id="sheepItForm_template">
        <table cellspacing="10" cellpadding="5">
            <tr>
                <td><?=__('Position Name','wpc')?></td>
                <td>
                    <input type="text" id="wpc_emb_positions_#index#_name" name="wpc_emb_config[positions][#index#][name]">
                </td>
            </tr>
            <tr>
                <td><?=__('Left','wpc')?></td>
                <td>
                    <input type="text" id="wpc_emb_positions_#index#_left" name="wpc_emb_config[positions][#index#][left]">
                </td>
            </tr>
            <tr>
                <td><?=__('Top','wpc')?></td>
                <td>
                    <input type="text" id="wpc_emb_positions_#index#_top" name="wpc_emb_config[positions][#index#][top]">
                </td>
            </tr>
            <tr>
                <td><?=__('Default','wpc')?></td>
                <td>
                    <input type="checkbox"  id="wpc_emb_positions_#index#_default" name="wpc_emb_config[positions][#index#][default]">
                </td>
            </tr>
        </table>
    </div>
    <div id="sheepItForm_noforms_template"></div>
    <div id="sheepItForm_controls">
        <div class="button button-primary button-small clr" style="display: inline-table" id="sheepItForm_add"><a><span class="dashicons dashicons-plus"></span><span><?=__('Add','wpc')?></span></a></div>
        <div class="button button-primary button-small clr" style="display: inline-table" id="sheepItForm_remove_last"><a><span class="dashicons dashicons-no"></span><span><?=__('Remove','wpc')?></span></a></div>
        <div id="sheepItForm_remove_all"><a><span>Remove all</span></a></div>
        <div id="sheepItForm_add_n">
            <input id="sheepItForm_add_n_input" type="text" size="4" />
            <div id="sheepItForm_add_n_button"><a><span>Add</span></a></div></div>
    </div>
</div>
<h4><?=__('Colors','wpc');?></h4>
<table cellspacing="10" cellpadding="5">
    <tr><th><?=__("Manufacturers","wpc")?></th><th>&nbsp;</th><th style="text-align: center">
            <select class="wpc_selection">
                <option value="">---</option>
                <option value="all"><?=__("All","wpc")?></option>
                <option value="none"><?=__("None","wpc")?></option>
                <?php if(!empty($all_manufacturer)){?>
                    <?php foreach ($all_manufacturer as $man){ ?>
                        <option value="<?=$man->term_id?>"><?=$man->name?></option>
                    <?php }?>
                <?php }?>
            </select>
        </th></tr>
    <?php if(!empty($all_Colors)){ foreach($all_Colors as $Color){
        $all_manufacturer_for_this_post= !empty(wp_get_post_terms( $Color->ID, 'wpc_color_manufacturer',array('fields'=>'ids')))?wp_get_post_terms( $Color->ID, 'wpc_color_manufacturer',array('fields'=>'ids')):array();
        ?>
        <tr>
            <td><?=$Color->post_title;?></td>
            <td style="background-color: <?=@$color_code;?>;width: 20px;height: 20px"></td>
            <td style="text-align: center">
                <?php  $selectedColor=isset($emb_config["colors"]) && in_array($Color->ID ,$emb_config["colors"])?'checked':'' ?>
                <input <?=$selectedColor;?> data-manu="<?=json_encode($all_manufacturer_for_this_post);?>" class="color_checkbox"  type="checkbox" value="<?=$Color->ID?>" name="wpc_emb_config[colors][]">
            </td>
        </tr>
    <?php }}?>
</table>
    <button type="submit" class="button button-primary"><?php _e('Save Data','wpc') ?></button>
</form>
</div>
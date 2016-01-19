<?php
if (!defined('ABSPATH')) exit;
$post_id = $_GET['post'];
$embroidery_step=get_post_meta($post_id,'_wpc_embroidery_step',true);
$embroidery_attribute= wp_get_post_terms($post_id,$embroidery_step);
$title = get_the_title($post_id);
$settings=get_option('wpc_settings');
$allColors=$settings["colors_data"];
$wpc_emb_logo_size=get_post_meta($post_id,'_wpc_emb_logo_size',true);
$wpc_emb_font_size=get_post_meta($post_id,'_wpc_emb_font_size',true);
$wpc_emb_no_embroidery=get_post_meta($post_id,'_wpc_emb_no_embroidery',true);
$wpc_emb_positions=get_post_meta($post_id,'_wpc_emb_positions',true);
$wpc_emb_colors=get_post_meta($post_id,'_wpc_emb_colors',true)?get_post_meta($post_id,'_wpc_emb_colors',true):array();
?>
<script type="text/javascript">
    var embroidery_config=true;
</script>
<h2><?php _e('Embroidery setting for', 'wpc') ?> <?= $title ?></h2>
<form action="" method="post" id="step_embroidery_form" data-id="<?=$post_id?>">
<table cellspacing="10" cellpadding="5">
    <tr>
       <th><?=__('Default Logo Image Size. (Based on 800 X 800)','wpc')?></th>
        <td>
            <table>
                <tr>
                    <td><input type="text" name="wpc_emb_logo_size[w]" value="<?=@$wpc_emb_logo_size['w']?>" placeholder="<?=__('Width','wpc')?>" size="10"></td>
                    <td>X</td>
                    <td><input type="text" name="wpc_emb_logo_size[h]" value="<?=@$wpc_emb_logo_size['h']?>" placeholder="<?=__('Height','wpc')?>" size="10"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th><?=__('Default Font Size','wpc')?></th>
        <td>
            <select name="wpc_emb_font_size">

                <option value="">---</option>
                <?php $options=get_option('wpc_settings'); $font_sizes=isset($options['font_size_data']) ? $options['font_size_data'] : array(); ?>
                <?php foreach($font_sizes as $size){  $selected=$wpc_emb_font_size==@$size['value'] ?'selected':'' ?> ?>
                    <option <?=$selected;?> value="<?=@$size['value']?>"><?=@$size['name'];?></option>
                <?php }?>
            </select>
        </td>
    </tr>
</table>
<table cellspacing="10" cellpadding="5">
    <tr><th colspan="2"><?=__('(No) Embroidery Term','wpc');?></th></tr>
    <?php if ($embroidery_attribute && is_array($embroidery_attribute)) { $term_array=array();foreach ($embroidery_attribute as $term) { ?>
        <tr>
           <td><?=$term->name?></td>
            <?php  $selected_check=$wpc_emb_no_embroidery==$term->slug?'checked':'' ?>
            <td><input type="radio" <?=$selected_check?> name="wpc_emb_no_embroidery" value="<?=$term->slug?>" ></td>
        </tr>
    <?php }}?>
</table>
<h4><?=__('Positions (Based on 800 X 800)','wpc');?></h4>
  <?php   $data_positions=array();
  if(!empty($wpc_emb_positions)){ foreach($wpc_emb_positions as $position){
    //array_push($data_color,array('wpc_#index#_color_name'=>$color['name'],'wpc_#index#_color_value'=>$color['value']));
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
                    <input type="text" id="wpc_emb_positions_#index#_name" name="wpc_emb_positions[#index#][name]">
                </td>
            </tr>
            <tr>
                <td><?=__('Left','wpc')?></td>
                <td>
                    <input type="text" id="wpc_emb_positions_#index#_left" name="wpc_emb_positions[#index#][left]">
                </td>
            </tr>
            <tr>
                <td><?=__('Top','wpc')?></td>
                <td>
                    <input type="text" id="wpc_emb_positions_#index#_top" name="wpc_emb_positions[#index#][top]">
                </td>
            </tr>
            <tr>
                <td><?=__('Default','wpc')?></td>
                <td>
                    <input type="checkbox"  id="wpc_emb_positions_#index#_default" name="wpc_emb_positions[#index#][default]">
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
    <tr>
        <td></td>
        <td></td>
        <td><a class="wpc_selectAllButton" href="#">(<?=__('All','wpc');?>)</a></td>
    </tr>
    <?php if($allColors && is_array($allColors)){ foreach($allColors as $color){ ?>
        <tr>
            <td><?=$color["name"]?></td>
            <td style="background-color: <?=$color["value"];?>;width: 20px;height: 20px"></td>
            <?php  $selectedColor=in_array($color["name"].'|'.$color["value"],$wpc_emb_colors)?'checked':'' ?>
            <td><input class="color_checkbox" <?=$selectedColor?> type="checkbox" value="<?=$color["name"]?>|<?=$color["value"];?>" name="wpc_emb_colors[]"> </td>
        </tr>
    <?php }}?>
</table>
    <button type="submit" class="button button-primary"><?php _e('Save Data','wpc') ?></button>
</form>
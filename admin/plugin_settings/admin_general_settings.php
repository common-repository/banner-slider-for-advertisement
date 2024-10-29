<?php
defined( 'ABSPATH' ) or die("No script kiddies please!");

global $wpdb,$wpwbs_banner_slider_posttype;
if(isset($_POST['bannerslider_general_setting']) && wp_verify_nonce( $_POST['bannerslider_general_setting'], 'bannerslider_general_setting_action'))
{
	$data_arr = $this->get_plugin_general_settings_data();
	if(!empty($data_arr)){
		for($i=0;$i<count($data_arr);$i++){
			$field_data = $data_arr[$i];
			$field_id = empty($field_data['field_id']) ? '' : $field_data['field_id'];
			if(!empty($field_id)){
				update_option($field_id,esc_attr(absint($_POST[$field_id])));
			}
			
		}
	}
	
	update_option('wpbsc_additional_css',esc_attr($_POST['wpbsc_additional_css']));
	
	do_action('wpw_banner_slider_admin_general_setting_save_data');
	?>	
	<form name="frm" action="edit.php" method="get">
	<input type="hidden" name="post_type" value="<?php echo WPWBS_BANNER_POST_TYPE;?>" />
	<input type="hidden" name="page" value="wpwbs_settings" />
	<input type="hidden" name="msg" value="success" />
	</form>
	<script type="text/javascript">document.frm.submit();</script>
	<?php
}

$wpbsc_additional_css = get_option('wpbsc_additional_css');
?>

<form name="bannerslider_frm" id="bannerslider_frm" action="" method="post">
<?php wp_nonce_field( 'bannerslider_general_setting_action', 'bannerslider_general_setting' ); ?>

<table cellpadding="5" cellspacing="10">

<?php do_action('wpw_banner_slider_admin_general_setting_start');?>

<?php
$data_arr = $this->get_plugin_general_settings_data();
if(!empty($data_arr)){
	for($i=0;$i<count($data_arr);$i++){
		$field_data = $data_arr[$i];
		$type = empty($field_data['type']) ? '' : $field_data['type'];
		$action = empty($field_data['action']) ? '' : $field_data['action'];		
		if(!empty($type)){
			if($type=='title'){
				$title = empty($field_data['title']) ? '' : $field_data['title'];
				$subtitle = empty($field_data['subtitle']) ? '' : $field_data['subtitle'];
				$url = empty($field_data['url']) ? '' : $field_data['url'];
				$urltext = empty($field_data['urltext']) ? __('click to see page','wpwbs') : $field_data['urltext'];
		?>
			<tr><td colspan="2">
				<h3><?php echo $title;?>
				<?php if(!empty($url)){?>
				&nbsp;&nbsp;&nbsp;(<a style="font-size:14px;font-weigh:normal;" target="_blank" href="<?php echo $url;?>"><?php echo $urltext;?></a>)
				<?php }?>
				</h3>
				<?php if(!empty($subtitle)){?>
				<h5><?php echo $subtitle;?></h5>
				<?php }?>
			</td></tr>
		<?php
			}elseif($type=='save_button'){?>
				<tr><td colspan="2">
					<input name="save" value="<?php _e('Save Settings','wpwbs');?>" class="button" type="submit">
				</td></tr>
		<?php		
			}
		}else{
			$this->set_plugin_settings_html($field_data);
		}
		
		if(!empty($action)){ do_action($action); }
	}
}

do_action('wpw_banner_slider_admin_general_search_setting_end');?>

<tr><td colspan="2">
<h3><?php _e('Other Settings','wpwbs');?></h3>
</td></tr>

<?php do_action('wpw_banner_slider_admin_general_other_setting_start');?>

<tr>
<td valign="top"><?php _e('Additional CSS','wpwbs');?></td>
<td>
<textarea name="wpbsc_additional_css" style="width:100%;height:100px;"><?php echo $wpbsc_additional_css;?></textarea>
<br /><small><?php _e('eg: insert CSS class directly. eg. --  .swiper-container{margin:0 auto;position:relative;}.swiper-slide{font-size: 18px;cursor:pointer;}','wpwbs');?> </small>
</td>
</tr>

<?php do_action('wpw_banner_slider_admin_general_other_setting_end');?>

<tr>
<td colspan="2">
<input type="submit" name="save" value="<?php _e('Save All Changes','wpwbs');?>" class="button-primary" />
</td>
</tr>

<?php do_action('wpw_banner_slider_admin_general_setting_end');?>

</table>
</form>
	
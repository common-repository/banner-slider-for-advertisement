<?php 

defined( 'ABSPATH' ) or die("No script kiddies please!");

class wpbscAdminSettings {
	public function __construct() {
		add_action('admin_menu', array($this,'add_admin_menu'));		
		//add_action('wp_ajax_of_ajax_post_action', array($this,'wpw_qs_ajax_callback'));
	}
	
	/**
	Plugin Admin Settings
	**/
	public function add_admin_menu(){
		$menu_title = __('Plugin Settings','wpwbs');
		add_submenu_page( 'edit.php?post_type='.WPWBS_BANNER_POST_TYPE, $menu_title, $menu_title, 'manage_options', 'wpwbs_settings', array($this,'wpw_edit_page') );
	}
	
	/**
	Admin Settings Interface
	**/
	public function wpw_edit_page()
	{
	?>
	<style>
	h2 {color: #464646;font-size: 23px;line-height: 29px; padding: 9px 15px 4px 0; font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",sans-serif; font-weight: normal;   margin: 0; text-shadow: 0 1px 0 #FFFFFF;}
	.imglisting,.postlisting{height:250px; overflow:auto; float:left; font-size:10px; margin-right:10px; border:solid 1px #E2E2E2; padding:0 10px; max-width:250px;}
	.imglisting,.postlisting p{ margin:2px 0;}
	.imglisting{margin-left:10px;}
	.attachment-thumbnail{width:60px; height:60px;}
	.note{font-size:14px; color:#000000;}
	.success{border:solid 1px #003300; background-color:#DDF1D3; font-weight:bold; color:#003300; padding:10px;}
	.eg{padding-left:30px;}
	ul.category li{float:left; padding:5px 10px; width:20%;}
	.nav-tab-wrapper{border-bottom: 1px solid #ccc;padding-bottom: 0;}
	.nav-tab.nav-tab-active{border-bottom: 1px solid #f1f1f1;}
	.fleft{float:left;}
	.fright{float:right;}
	</style>
	<div class="icon32 icon32-posts-place" id="icon-edit"><br></div>
	<h2><?php _e('Banner Slider Settings','wpwbs');?></h2>
	<?php if(!empty($_GET['msg']) && $_GET['msg']=='success'){?>
	<p class="success"><?php _e('Settings Saved successfully ...','wpwbs');?></p>
	<?php }?>
	<?php $tab = empty($_GET['tab']) ? '' : $_GET['tab'];?>
	<h2 class="nav-tab-wrapper">
		<a href="edit.php?post_type=<?php echo WPWBS_BANNER_POST_TYPE;?>&amp;page=wpwbs_settings&amp;tab=general" class="nav-tab <?php if($tab=='' || $tab=='general')echo 'nav-tab-active';?>"><?php _e('General','wpwbs');?></a>
		<a href="edit.php?post_type=<?php echo WPWBS_BANNER_POST_TYPE;?>&amp;page=wpwbs_settings&amp;tab=document" class="nav-tab <?php if($tab=='document')echo 'nav-tab-active';?>"><?php _e('Document','wpwbs');?></a>
		<?php do_action('wpw_banner_slider_admin_settings_new_tab');?>
	</h2>
	<?php
		$file = $this->get_settings_file();
		if(!empty($file) && file_exists($file))
			include_once($file);	
		else
			echo '<h4>'.sprintf(__('Please try correct file or check the file path : %s','wpwbs'),$file).'</h4>';
		
		
	}
	
	public function get_settings_file(){
		$tab = empty($_GET['tab']) ? '' : $_GET['tab'];
		if($tab=='document'){
			$file = WPW_BANNER_SLIDER_PATH.'/admin/plugin_settings/admin_settings_document.php';
		}else{
			$file = WPW_BANNER_SLIDER_PATH.'/admin/plugin_settings/admin_general_settings.php';
		}
		return apply_filters('wpw_banner_slider_admin_settings_new_tab_file',$file);
	}
	
	public function get_plugin_general_settings_data(){
		$settings = array();
		$settings[] = array(
							'title'		=>  __('Home Page Settings','wpwbs'),
							'subtitle'	=>  '',
							'url'		=>  home_url(),
							'urltext'	=>  __('click to see home page','wpwbs'),
							'type'		=>  'title',
							'action'	=>  'wpw_banner_slider_admin_general_home_title',
							);
		$settings[] = array(
							'title'		=>  __('Above Header','wpwbs'),
							'desc'		=>  '',
							'field_id'	=>	'wpbsc_before_header_home_id',
							'action'	=>  'wpw_banner_slider_admin_general_home_above_header',
							);
		$settings[] = array(
							'title'		=>  __('Below Header','wpwbs'),
							'desc'		=>  '',
							'field_id'	=>	'wpbsc_after_header_home_id',
							'action'	=>  'wpw_banner_slider_admin_general_home_below_header',
							);
		$settings[] = array(
							'title'		=>  __('Above Footer','wpwbs'),
							'desc'		=>  '',
							'field_id'	=>	'wpbsc_before_footer_home_id',
							'action'	=>  'wpw_banner_slider_admin_general_home_above_footer',
							);
		$settings[] = array(
							'title'		=>  __('Below Footer','wpwbs'),
							'desc'		=>  '',
							'field_id'	=>	'wpbsc_after_footer_home_id',
							'action'	=>  'wpw_banner_slider_admin_general_home_below_header',
							);
		/*$settings[] = array(
							'title'		=>  '',
							'type'		=>  'save_button',
							'action'	=>  'wpw_banner_slider_admin_general_home_save_button',
							);
		*/					
		/**Search Settings**/
		$settings[] = array(
							'title'		=>  __('Search Page Settings','wpwbs'),
							'subtitle'	=>  '',
							'url'		=>  site_url('?s='),
							'urltext'	=>  __('click to see search page','wpwbs'),
							'type'		=>  'title',
							'action'	=>  'wpw_banner_slider_admin_general_search_title',
							'action'	=>  'wpw_banner_slider_admin_general_search_title',
							);
		$settings[] = array(
							'title'		=>  __('Above Header','wpwbs'),
							'desc'		=>  '',
							'field_id'	=>	'wpbsc_before_header_search_id',
							'action'	=>  'wpw_banner_slider_admin_general_search_above_header',
							);
		$settings[] = array(
							'title'		=>  __('Below Header','wpwbs'),
							'desc'		=>  '',
							'field_id'	=>	'wpbsc_after_header_search_id',
							'action'	=>  'wpw_banner_slider_admin_general_search_below_header',
							);
		$settings[] = array(
							'title'		=>  __('Above Footer','wpwbs'),
							'desc'		=>  '',
							'field_id'	=>	'wpbsc_before_footer_search_id',
							'action'	=>  'wpw_banner_slider_admin_general_search_above_footer',
							);
		$settings[] = array(
							'title'		=>  __('Below Footer','wpwbs'),
							'desc'		=>  '',
							'field_id'	=>	'wpbsc_after_footer_search_id',
							'action'	=>  'wpw_banner_slider_admin_general_search_below_footer',
							);
									
		return apply_filters('wpw_banner_slider_admin_general_settings_data_array',$settings);
	}
	
	public function set_plugin_settings_html($args=array()){
		global $wpwbs_banner_slider_posttype;
		$field_id = empty($args['field_id']) ? 0 : $args['field_id'];
		$title = empty($args['title']) ? '' : $args['title'];
		$desc = empty($args['desc']) ? '' : $args['desc'];
		$field_val = get_option($field_id);
	?>
		<tr>
			<td valign="top"><?php echo $title;?></td>
			<td>
			<select name="<?php echo $field_id;?>">
			<?php 
			$wpwbs_banner_slider_posttype->get_slider_select_options($field_val);
			?>
			</select>
			<?php if(!empty($field_val)):?>
			&nbsp;&nbsp;<a target="_blank" href="post.php?post=<?php echo $field_val;?>&action=edit"><?php _e('edit banner','wpwbs');?></a>
			<?php endif;?>
			<?php if(!empty($desc)){?>
				<div class="admin_field_desc"><?php echo $desc;?></div>
			<?php }?>
			</td>
		</tr>
		<?php 
		$action_var = $field_id.'_admin_settings';
		do_action($action_var);
		
	}
}

global $wpbs_admin_settings;
$wpbs_admin_settings = new wpbscAdminSettings();

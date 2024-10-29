<?php
/*
Plugin Name: Banner Slider for Advertisement
Plugin URI: http://wpwebs.com/demo/wp-banner-slider/
Description: Display banner advertisement slider for home, category pages, tag pages & search page. Please visit the author link @ <a href="http://wpwebs.com/" target="_blank">http://wpwebs.com/</a>
Version: 1.0.2
Author: WPWebs Team
Author URI: http://wpwebs.com/
Text Domain: wpwbs
Domain Path: /languages
License: GPL2
*/

defined( 'ABSPATH' ) or die("No script kiddies please!");

$wpwbs_plugin_dir_path = dirname(__FILE__);
$wpwbs_plugin_dir_url = plugins_url('', __FILE__);
$wpwbs_plugin_dir_url = str_replace('http://','//',$wpwbs_plugin_dir_url);

define('WPW_BANNER_SLIDER_PATH',$wpwbs_plugin_dir_path);
define('WPW_BANNER_SLIDER_URL',$wpwbs_plugin_dir_url);

if(!class_exists('wpwBannerSlider')){
	class wpwBannerSlider {
		function __construct(){
			add_action('plugins_loaded',array($this,'load_plugin_textdomain_fun'));
			add_action('init', array($this,'wpw_init'));
			
			include_once('includes/functions/banner_slider_functions.php');
			include_once('includes/functions/banner_slider_widgets.php');			
			
		}
		
		/**
		Plugin load Function
		**/
		function load_plugin_textdomain_fun(){
			load_plugin_textdomain( 'wpwbs', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
		}
		
		
		/**
		Plugin Init Function
		**/
		function wpw_init(){
			define('WPWBS_BANNER_POST_TYPE','wpwbs');
			define('APP_POST_TYPE','post');
			
			$total_banners_number = apply_filters('wpw_slider_total_banners_number_settings',5);
			define('WPWBS_TOTAL_SLIDER_BANNERS_NUMBER',$total_banners_number);
			
			$taxonomy01 = apply_filters('wpw_banner_slider_taxonomy_type01','category');
			define('WPBSC_TAXONOMY_01',$taxonomy01);
			
			$taxonomy02 = apply_filters('wpw_banner_slider_taxonomy_type02','post_tag');
			define('WPBSC_TAXONOMY_02',$taxonomy02);
			
			$taxonomy03 = apply_filters('wpw_banner_slider_taxonomy_type03','');
			define('WPBSC_TAXONOMY_03',$taxonomy03);
			
			$taxonomy04 = apply_filters('wpw_banner_slider_taxonomy_type04','');
			define('WPBSC_TAXONOMY_04',$taxonomy04);
			
			$taxonomy05 = apply_filters('wpw_banner_slider_taxonomy_type05','');
			define('WPBSC_TAXONOMY_05',$taxonomy05);
			
			$taxonomy06 = apply_filters('wpw_banner_slider_taxonomy_type06','');
			define('WPBSC_TAXONOMY_06',$taxonomy06);
			
			$taxonomy07 = apply_filters('wpw_banner_slider_taxonomy_type07','');
			define('WPBSC_TAXONOMY_07',$taxonomy07);
			
			if(is_admin() && current_user_can('manage_options')){ /*Security checking for site admin only can access plugin settings*/
				include_once('admin/banners_posttype.php');
				include_once('admin/banners_cats.php');
				include_once('admin/plugin_settings/admin_settings.php');
			}		
		}
		
		
	}
}

global $wpw_banner_slider;
$wpw_banner_slider = new wpwBannerSlider();
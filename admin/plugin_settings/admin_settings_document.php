<?php defined( 'ABSPATH' ) or die("No script kiddies please!");?>

<table cellpadding="5" cellspacing="10">
	<?php do_action('wpw_banner_slider_admin_document_setting_start');?>
	
	<?php do_action('wpw_banner_slider_admin_document_note');?>
	<tr><td>
		<?php do_action('wpw_banner_slider_admin_document_header_content_start');?>
		<h2><?php _e('Header Banner Display Settings','wpwbs');?> </h2>
		<h4>
		<?php 
		$header_title = __('Header is common for whole site generally & related wordpress file from theme folder should be &quot; header.php &quot;, so we should add related wordpress action hook code in  &quot;header.php&quot; file at << above header HTML code start >> & << below header HTML code end >> but outside the php code.','wpwbs');
		echo apply_filters('wpw_banner_slider_admin_document_header_title',$header_title);
		?> 
		</h4>
		
		<div>
			<h3><?php _e('Display banner ABOVE or BEFORE header','wpwbs');?> </h3>
			<h5><?php _e('Copy below code and add in related file','wpwbs');?> </h5>
			<code>&lt;?php do_action('wpw_banner_slider_before_header');?&gt;</code>
			<br><br><br>
		</div>
		<?php do_action('wpw_banner_slider_admin_document_below_header');?>
		<div>
			<h3><?php _e('Display banner BELOW or AFTER header','wpwbs');?> </h3>
			<h5><?php _e('Copy below code and add in related file','wpwbs');?> </h5>
			<code>&lt;?php do_action('wpw_banner_slider_after_header');?&gt;</code>
		</div>
		<?php
			$header_file_image = '<br><br><h4>'.__('Image for - How to add header banner code in header.php file','wpwbs').'</h4><img style="border:solid 2px brown;max-width:100%;height:auto;" src="'.WPW_BANNER_SLIDER_URL.'/admin/plugin_settings/images/header-php-settings.png'.'" alt="" />';
			echo apply_filters('wpw_banner_slider_admin_document_header_file_image',$header_file_image);
		?>
		<?php do_action('wpw_banner_slider_admin_document_header_content_end');?>
	</td></tr>
	
	<?php do_action('wpw_banner_slider_admin_document_header_settings_end');?>
	
	
	<tr><td>
	<br><br><br>
		<?php do_action('wpw_banner_slider_admin_document_footer_content_start');?>
		<h2><?php _e('Footer Banner Display Settings','wpwbs');?> </h2>
		<h4><?php 
		$footer_title = __('Footer is common for whole site generally & related wordpress file from theme folder should be &quot; footer.php &quot;, so we should add related wordpress action hook code in  &quot;footer.php&quot; file at << above footer HTML code start >> & << below footer HTML code end >> but outside the php code.','wpwbs');
		echo apply_filters('wpw_banner_slider_admin_document_footer_title',$footer_title);
		?> </h4>
		<?php do_action('wpw_banner_slider_admin_document_footer_below_title');?>
		<div>
			<h3><?php _e('Display banner ABOVE or BEFORE footer','wpwbs');?> </h3>
			<h5><?php _e('Copy below code and add in related file','wpwbs');?> </h5>
			<code>&lt;?php do_action('wpw_banner_slider_before_footer');?&gt;</code>
			<br><br><br>
		</div>
		<div>
			<h3><?php _e('Display banner BELOW or AFTER footer','wpwbs');?> </h3>
			<h5><?php _e('Copy below code and add in related file','wpwbs');?> </h5>
			<code>&lt;?php do_action('wpw_banner_slider_after_footer');?&gt;</code>
		</div>
		<?php
			$header_file_image = '<br><br><h4>'.__('Image for - How to add footer banner code in footer.php file','wpwbs').'</h4><img style="border:solid 2px brown;max-width:100%;height:auto;" src="'.WPW_BANNER_SLIDER_URL.'/admin/plugin_settings/images/footer-php-settings.png'.'" alt="" />';
			echo apply_filters('wpw_banner_slider_admin_document_header_file_image',$header_file_image);
		?>
		
		
		<?php do_action('wpw_banner_slider_admin_document_footer_content_end');?>	
	</td></tr>
	<?php do_action('wpw_banner_slider_admin_document_footer_settings');?>
	
	<?php do_action('wpw_banner_slider_admin_document_setting_end');?>
</table>
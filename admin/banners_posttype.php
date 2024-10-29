<?php
defined( 'ABSPATH' ) or die("No script kiddies please!");

if(!class_exists('wpwBannerSliderPostType')){
	class wpwBannerSliderPostType {
		public $banner_slider_post_type;
		public $banner_slider_speed;
		public $slider_per_view_arr;
		public function __construct(){
			$this->banner_slider_post_type = WPWBS_BANNER_POST_TYPE;
			$this->banner_slider_speed = 2500;
			add_action('init', array($this,'wpw_posttype'),99);	//init to add new banner slider post type
			add_action('admin_init', array($this,'wpw_custom_box'), 1); //custom box
			add_action('save_post', array($this,'wpw_save_postdata')); //save custom data			
			add_filter('manage_edit-'.$this->banner_slider_post_type.'_columns', array($this,'wpw_edit_banner_columns')); //banner slider post type >> add new list with columns title
			add_action('manage_'.$this->banner_slider_post_type.'_posts_custom_column', array($this,'wpw_custom_banner_columns'), 2 ); //banner slider post type >> add new list data
			add_action('admin_enqueue_scripts', array($this,'admin_scripts')); //add admin css & js
		}
		
		public function wpw_posttype(){
			$slider_per_view_arr = array();
			for($b=1;$b<=WPWBS_TOTAL_SLIDER_BANNERS_NUMBER;$b++){
				$slider_per_view_arr[$b] = $b;
			}
			$this->slider_per_view_arr = apply_filters('wpw_banner_slider_images_per_view_array',$slider_per_view_arr);
			
			/**Banner Slider Advertisement Post Type**/
			register_post_type(	$this->banner_slider_post_type, 
				array(	'label' 			=> __('Banner Slider','wpwbs'),
						'labels' => array(	
						'name' 					=> __('Banner Slider','wpwbs'),
						'singular_name' 		=> __('Banner Slider','wpwbs'),
						'add_new' 				=> __('Add Banner Slider','wpwbs'),
						'add_new_item' 			=> __('Add New Banner Slider','wpwbs'),
						'edit' 					=> __('Edit','wpwbs'),
						'edit_item' 			=> __('Edit Banner Slider','wpwbs'),
						'new_item' 				=> __('New Banner Slider','wpwbs'),
						'view_item'				=> __('View Banner Slider','wpwbs'),
						'search_items' 			=> __('Search Banner Slider','wpwbs'),
						'not_found' 			=> __('No Banner Slider found','wpwbs'),
						'not_found_in_trash' 	=> __('No Banner Slider found in trash','wpwbs')	),
				'public' 			=> true,
				'can_export'		=> true,
				'show_ui' 			=> true, // UI in admin panel
				'_builtin' 			=> false, // It's a custom post type, not built in
				'_edit_link' 		=> 'post.php?post=%d',
				'capability_type' 	=> 'post',
				'hierarchical' 		=> false,
				'rewrite' 			=> array("slug" => $this->banner_slider_post_type), // Permalinks
				'query_var' 		=> $this->banner_slider_post_type, // This goes to the WP_Query schema
				'supports' 			=> array('title') , //title,editor,author,thumbnail,excerpt,trackbacks,custom-fields,comments,revisions,page-attributes,post-formats
				//'menu_position' 	=> 5,
				'show_in_nav_menus'	=> false
				)
			);

		}
		
		
		/* Adds a box to the main column on the Post and Page edit screens */
		public function wpw_custom_box() {
			add_meta_box( 'wpbsc_sectionid', __( 'Banner Slider Settings','wpwbs'),array($this,'wpw_inner_custom_box'), $this->banner_slider_post_type,'normal', 'high' );
		}

		/* Prints the box content */
		public function wpw_inner_custom_box() {
			global $post;			
		?>
		<style>
		.subtitle{background-color:#f0fcf0;padding:5px;}
		.subtitle td{padding:10px;}
		.wpbsc_image{max-width:80px;max-height:60px;height:auto;}
		.subtitle strong{color:#000;}
		#wpbsc_sectionid table tr td{vertical-align: top;}
		</style>
		<table border="0" cellpadding="5" cellspacing="5" width="100%">
			<?php do_action('wpw_banner_slider_admin_post_type_settngs_start',$post);?>
			<tr>
				<td colspan="2" align="left">
				<fieldset style="border: 1px solid #ccc;">
					<legend><strong> <?php _e("Slider Banners",'wpwbs')?> </strong></legend>
					<table border="0" cellpadding="5" cellspacing="5" width="100%">
						<tr>
							<td colspan="2" align="left"><code><?php _e("All banner image width & height should be same. For banner slider to display properly.",'wpwbs')?></code></td>
						</tr>
						
						<?php 
						for($bm=1;$bm<=WPWBS_TOTAL_SLIDER_BANNERS_NUMBER;$bm++){
							if(strlen($bm)==1){
								$loop_number = '0'.$bm;
							}else{
								$loop_number = $bm;
							}
						?>
							<tr class="subtitle">
								<td colspan="2" align="left"><strong><?php printf(__("Banner %s Settings",'wpwbs'),$bm);?></strong></td>	
							</tr>
							<tr>
								<td width="20%" valign="top"><?php _e("Banner Image URL:",'wpwbs')?>
								<?php 
								$image_var = 'image'.$bm;
								$url_var = 'url'.$bm;
								$image1 = get_post_meta($post->ID,$image_var,true);
								if($image1)echo '<br /><img src="'.$image1.'" alt="" class="wpbsc_image" />';?>
								</td>
								<td>
								<input type="text" id="<?php echo $image_var;?>" name="<?php echo $image_var;?>" class="<?php echo $image_var;?>" style="width:80%;" value="<?php echo $image1;?>" />
								<input class="wpbsc_upload_image_button" onclick="wpw_upload_img('<?php echo $image_var;?>')" type="button" value="<?php _e('Upload Image','wpwbs');?>" />
								<br /><small><?php _e("eg:",'wpwbs')?> http://wpwebs.com/images/<?php echo $image_var;?>.jpg</small>
								</td>
							</tr>
							<tr>
								<td width="20%"><?php _e("Banner Click URL:",'wpwbs')?>
								</td>
								<td><input type="text" id= "<?php echo $url_var;?>" name="<?php echo $url_var;?>" class="<?php echo $url_var;?>" style="width:80%;" value="<?php echo get_post_meta($post->ID,$url_var,true);?>">
								<br /><small><?php _e("eg:",'wpwbs')?> http://wpwebs.com/about-us/<?php echo $url_var;?>/</small>
								</td>
							</tr>
							<?php $action_var = 'wpw_banner_slider_post_type_banner'.$loop_number.'_settngs';
							do_action($action_var,$post);?>
						<?php
						}
						?>
						
						<?php do_action('wpw_banner_slider_add_custom_field_banner_settings',$post); ?>
					</table>
					</fieldset>
				</td>	
			</tr>
			
			<?php do_action('wpw_banner_slider_admin_after_banners',$post);?>
			<tr><td colspan="2"><h4 style="color:brown;margin:0;text-decoration:underline;"><?php _e('Slider Settings','wpwbs');?></h4></td></tr>
			<tr>
				<td width="20%" valign="top"><?php _e('Pagination','wpwbs');?></td>
				<td>
				<?php $disable_pagination = get_post_meta($post->ID,'disable_pagination',true);?>
				<label for="disable_pagination"><input type="checkbox" name="disable_pagination" id="disable_pagination" value="1" <?php if($disable_pagination){?>checked="checked"<?php }?> /> <?php _e("Disable Pagination?",'wpwbs')?></label>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top"><?php _e('Previous/Next','wpwbs');?></td>
				<td>
				<?php $disable_prev_next = get_post_meta($post->ID,'disable_prev_next',true);?>
				<label for="disable_prev_next"><input type="checkbox" name="disable_prev_next" id="disable_prev_next" value="1" <?php if($disable_prev_next){?>checked="checked"<?php }?> /> <?php _e("Disable Previous & Next?",'wpwbs')?></label>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top"><?php _e('Autoplay','wpwbs');?></td>
				<td>
				<?php $disable_auto_play = get_post_meta($post->ID,'disable_auto_play',true);?>
				<label for="disable_auto_play"><input type="checkbox" name="disable_auto_play" id="disable_auto_play" value="1" <?php if($disable_auto_play){?>checked="checked"<?php }?> /> <?php _e("Stop Autoplay?",'wpwbs')?></label>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top"><?php _e('Infinite Loop','wpwbs');?></td>
				<td>
				<?php $disable_loop = get_post_meta($post->ID,'disable_loop',true);?>
				<label for="disable_loop"><input type="checkbox" name="disable_loop" id="disable_loop" value="1" <?php if($disable_loop){?>checked="checked"<?php }?> /> <?php _e("Stop Infinite Loop?",'wpwbs')?></label>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top"><?php _e('Auto Height','wpwbs');?></td>
				<td>
				<?php $disable_auto_height = get_post_meta($post->ID,'disable_auto_height',true);?>
				<label for="disable_auto_height"><input type="checkbox" name="disable_auto_height" id="disable_auto_height" value="1" <?php if($disable_auto_height){?>checked="checked"<?php }?> /> <?php _e("Stop to adjust slider height as per banner size??",'wpwbs')?></label>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top"><?php _e('Keyboard Control','wpwbs');?></td>
				<td>
				<?php $disable_keyboard = get_post_meta($post->ID,'disable_keyboard',true);?>
				<label for="disable_keyboard"><input type="checkbox" name="disable_keyboard" id="disable_keyboard" value="1" <?php if($disable_keyboard){?>checked="checked"<?php }?> /> <?php _e("Stop Keyboard Next & Previous Control?",'wpwbs')?></label>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top">
				<?php _e('Slider Effect','wpwbs');?>
				</td>
				<td>
				<?php $slider_effect = get_post_meta($post->ID,'slider_effect',true);?>
				<select name="slider_effect" id="slider_effect">
					<option value=""><?php _e('Default Effect','wpwbs');?></option>
					<option <?php if($slider_effect=='fade'){echo 'selected';}?> value="fade"><?php _e('Fade','wpwbs');?></option>
					<?php do_action('wpw_admin_banner_slider_effects_select',$post);?>
				</select>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top">
				<?php _e('Slider timer','wpwbs');?>
				</td>
				<td>
				<?php $slider_time = get_post_meta($post->ID,'slider_time',true);
				if(!$slider_time){$slider_time=$this->banner_slider_speed;}elseif($slider_time<1000){$slider_time=$this->banner_slider_speed;}
				?>
				<label for="slider_time"><input type="number" name="slider_time" value="<?php echo $slider_time;?>" /></label>
				<small><?php printf(__('delay between transitions mean slider timer to slide next banner. Default is %s milliseconds.','wpwbs'),$this->banner_slider_speed);?></small>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top"><?php _e('Slider Width (in pixels)','wpwbs');?></td>
				<td>
				<?php $content_width = get_post_meta($post->ID,'content_width',true);?>
				<label for="content_width">
				<input type="text" name="content_width" id="content_width" value="<?php echo $content_width;?>"  /> 
				<br><small><?php _e("keep blank for full screen size. Set slider width in pixels. eg : 960",'wpwbs')?><small></label>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top"><?php _e('Random Banners','wpwbs');?></td>
				<td>
				<?php $random_banners = get_post_meta($post->ID,'random_banners',true);?>
				<label for="random_banners"><input type="checkbox" name="random_banners" id="random_banners" value="1" <?php if($random_banners){?>checked="checked"<?php }?> /> <?php _e("Display slider banners randomly?",'wpwbs')?></label>
				</td>
			</tr>
			<?php do_action('wpw_admin_banner_slider_after_random_settings',$post);?>
			<tr>
				<td width="20%" valign="top"><?php _e('On Mobile','wpwbs');?></td>
				<td>
				<?php $disable_on_mobile = get_post_meta($post->ID,'disable_on_mobile',true);?>
				<label for="disable_on_mobile"><input type="checkbox" name="disable_on_mobile" id="disable_on_mobile" value="1" <?php if($disable_on_mobile){?>checked="checked"<?php }?> /> <?php _e("Disable on mobile device?",'wpwbs')?></label>
				</td>
			</tr>
			
			<?php do_action('wpw_admin_banner_slider_after_mobile_settings',$post);?>
			<tr><td colspan="2"><h4 style="color:brown;margin:0;text-decoration:underline;"><?php _e('Gallery Slider Settings','wpwbs');?></h4></td></tr>
			<tr>
				<td width="20%" valign="top"><?php _e('Banners per view','wpwbs');?></td>
				<td>
				<?php $slide_per_view = get_post_meta($post->ID,'slide_per_view',true);
				if(empty($slide_per_view) || $slide_per_view<1)$slide_per_view=1;
				?>
				<select name="slide_per_view" id="slide_per_view" >
				<?php
				foreach($this->slider_per_view_arr as $key=>$val){
				?>
				<option value="<?php echo $key;?>" <?php if($key==$slide_per_view)echo 'selected';?>><?php echo $val;?></option>
				<?php				
				}
				?>
				</select>
				<br /><small>- <?php _e("slider per view to display. If you want more than one banner per view. default is : 1.",'wpwbs')?></small>
				<br /><small>- <?php _e("this settings work only for default Slider Effect.",'wpwbs')?></small>
				</td>
			</tr>
			<tr>
				<td width="20%" valign="top"><?php _e('Space Between','wpwbs');?></td>
				<td>
				<?php $space_between = get_post_meta($post->ID,'space_between',true);
				if(empty($space_between) || $space_between<0)$space_between=30;
				?>
				<input type="text" name="space_between" id="space_between" value="<?php echo $space_between;?>" />
				<br /><small><?php _e("space gap between banner images. default is : 30",'wpwbs')?></small>
				</td>
			</tr>
			<?php do_action('wpw_admin_banner_slider_after_gallery_settings',$post);?>
			<tr><td colspan="2">&nbsp;</td></tr>
		</table>
		<script type="text/javascript">
		jQuery(document).ready( function( $ ) {
			jQuery('.wpbsc_upload_image_button').click(function() {
				formfield = jQuery('#'+banner_input_var).attr('name');
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true' );
				return false;
			});

			window.send_to_editor = function(html) {
				if(html.toLowerCase().indexOf("href=") < 0)html = '<a href="#">'+html+'</a>';
				else if(typeof(imgurl)=='undefined' || imgurl=='')html = '<a href="#">'+html+'</a>';
				imgurl = jQuery('img',html).attr('src');
				jQuery('#'+banner_input_var).val(imgurl);
				tb_remove();
			}
		});

		function wpw_upload_img(input_var){
			banner_input_var = input_var;
		}
		
		<?php do_action('wpw_banner_slider_admin_javascript_settings',$post);?>
		</script>
		<?php
		}
		
		public function wpw_save_postdata( $post_id ) {
		 $post_id = absint($post_id);
		  // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
		  // to do anything
		  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
			return $post_id;
		  
		  // Check permissions
		  if (!empty($_POST['post_type']) && $this->banner_slider_post_type == $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) )
			  return $post_id;
			  
				// OK, we're authenticated: we need to find and save the data
				/**settings**/
				$slider_time = empty($_POST['slider_time']) ? 2500 : absint($_POST['slider_time']);
				if(!$slider_time){$slider_time=$this->banner_slider_speed;}elseif($slider_time<1000){$slider_time=$this->banner_slider_speed;}
				
				$disable_pagination = empty($_POST['disable_pagination']) ? '' : absint($_POST['disable_pagination']);
				update_post_meta($post_id,'disable_pagination',$disable_pagination);
				$disable_prev_next = empty($_POST['disable_prev_next']) ? '' : absint($_POST['disable_prev_next']);
				update_post_meta($post_id,'disable_prev_next',$disable_prev_next);
				$disable_auto_play = empty($_POST['disable_auto_play']) ? '' : absint($_POST['disable_auto_play']);
				update_post_meta($post_id,'disable_auto_play',$disable_auto_play);
				$disable_loop = empty($_POST['disable_loop']) ? '' : absint($_POST['disable_loop']);
				update_post_meta($post_id,'disable_loop',$disable_loop);
				$disable_auto_height = empty($_POST['disable_auto_height']) ? '' : absint($_POST['disable_auto_height']);
				update_post_meta($post_id,'disable_auto_height',$disable_auto_height);
				$disable_keyboard = empty($_POST['disable_keyboard']) ? '' : absint($_POST['disable_keyboard']);
				update_post_meta($post_id,'disable_keyboard',$disable_keyboard);
				$slider_effect = empty($_POST['slider_effect']) ? '' : esc_attr($_POST['slider_effect']);
				update_post_meta($post_id,'slider_effect',$slider_effect);
				update_post_meta($post_id,'slider_time',$slider_time);
				$disable_on_mobile = empty($_POST['disable_on_mobile']) ? '' : absint($_POST['disable_on_mobile']);
				update_post_meta($post_id,'disable_on_mobile',$disable_on_mobile);
				$content_width = empty($_POST['content_width']) ? '' : absint($_POST['content_width']);
				if($content_width<10)$content_width='';
				
				update_post_meta($post_id,'content_width',$content_width);
				$random_banners = empty($_POST['random_banners']) ? '' : absint($_POST['random_banners']);
				update_post_meta($post_id,'random_banners',$random_banners);
				if(!empty($_POST['slide_per_view']) && $_POST['slide_per_view']<1)$_POST['slide_per_view']=1;
				if(!empty($_POST['slide_per_view']) && $_POST['slide_per_view']>10)$_POST['slide_per_view']=5;
				update_post_meta($post_id,'slide_per_view',$_POST['slide_per_view']);
				if(empty($_POST['space_between']) || $_POST['space_between']<0)$_POST['space_between']=30;
				update_post_meta($post_id,'space_between',absint($_POST['space_between']));
				
				/**images**/
				for($b=1;$b<=WPWBS_TOTAL_SLIDER_BANNERS_NUMBER;$b++){					
					$image_var = 'image'.$b;
					$url_var = 'url'.$b;
					$_POST[$image_var] = empty($_POST[$image_var]) ? '' : esc_url_raw($_POST[$image_var]);
					$_POST[$url_var] = empty($_POST[$url_var]) ? '' : esc_url_raw($_POST[$url_var]);
					update_post_meta($post_id,$image_var,$_POST[$image_var]);
					update_post_meta($post_id,$url_var,$_POST[$url_var]);
				}
				
				do_action('wpw_banner_slider_save_custom_field',array('post_id'=>$post_id));
				
		  } 
		  
		}
		
		/********************************
		Column Settings
		********************************/
		public function wpw_edit_banner_columns($columns){
			$columns = array();
			$columns["cb"] = "<input type=\"checkbox\" />";
			$columns["title"] = __("Title", 'wpwbs');
			$columns["bimages"] = __("Banners", 'wpwbs');
			$columns["botherdetails"] = __("Other Details", 'wpwbs');
			return $columns;
		}
		
		public function wpw_custom_banner_columns( $column ) {
			global $post;
			switch ($column) {
				case "bimages" :
					
					for($b=1;$b<=WPWBS_TOTAL_SLIDER_BANNERS_NUMBER;$b++){
						$image_var = 'image'.$b;
						$image = get_post_meta($post->ID,$image_var,true);
						if(!empty($image))echo '<img src="'.esc_url($image).'" alt="" style="max-width:80px;height:auto;max-height:50px;margin:1px 3px;" />';
					}
					
					do_action('wpw_banner_slider_custom_field_image_column',array('post_id'=>$post->ID));
					
				break;
				case "botherdetails" :
					$pagination = get_post_meta($post->ID,'disable_pagination',true);
					$prev_next = get_post_meta($post->ID,'disable_prev_next',true);
					$auto_play = get_post_meta($post->ID,'disable_auto_play',true);
					$loop = get_post_meta($post->ID,'disable_loop',true);
					$auto_height = get_post_meta($post->ID,'disable_auto_height',true);
					$keyboard = get_post_meta($post->ID,'disable_keyboard',true);
					$effect = get_post_meta($post->ID,'slider_effect',true);
					$on_mobile = get_post_meta($post->ID,'disable_on_mobile',true);
					$time = get_post_meta($post->ID,'slider_time',true);
					if(!$effect)$effect=__('default','wpwbs');
					
					echo __('pagination:','wpwbs').' ';
					if($pagination==1)echo __('disabled','wpwbs');else echo __('enabled','wpwbs');
					
					echo '<br />'.__('previous next:','wpwbs').' ';
					if($prev_next==1)echo __('disabled','wpwbs');else echo __('enabled','wpwbs');
					
					echo '<br />'.__('slider loop:','wpwbs').' ';
					if($loop==1)echo __('disabled','wpwbs');else echo __('enabled','wpwbs');
					
					echo '<br />'.__('auto height:','wpwbs').' ';
					if($auto_height==1)echo __('disabled','wpwbs');else echo __('enabled','wpwbs');
					
					echo '<br />'.__('keyboard control:','wpwbs').' ';
					if($keyboard==1)echo __('disabled','wpwbs');else echo __('enabled','wpwbs');
					
					echo '<br />'.__('slider effect:','wpwbs').' '.$effect;
					
					echo '<br />'.__('autoplay:','wpwbs').' ';
					if($auto_play==1)echo __('disabled','wpwbs');else echo __('enabled','wpwbs');
					
					echo '<br />'.__('autoplay time:','wpwbs').' '.$time;
					echo '<br />'.__('on mobile:','wpwbs').' ';
					if($on_mobile==1)echo __('disabled','wpwbs');else echo __('enabled','wpwbs');
					
					do_action('wpw_banner_slider_custom_field_details_column',array('post_id'=>$post->ID));
					
				break;
			}
		}
		
		public function admin_scripts() {
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
			echo '<script>var banner_input_var = "";</script>';	
				
		}
		
		public function get_slider_select_options($selected_id=0){
		?>
			<option value=""><?php _e('-- Select One --','wpwbs');?></option>
			<?php 
			global $wpdb;
			$banners_ids = $wpdb->get_col("select ID from $wpdb->posts where post_type='".$this->banner_slider_post_type."' and post_status='publish' order by post_title asc");
			if($banners_ids){
				for($i=0;$i<count($banners_ids);$i++){
					$title = get_the_title($banners_ids[$i]);
					if(empty($title))$title = sprintf(__('No Title ID:#%s','wpwbs'),$banners_ids[$i]);
			?>
				<option <?php if($selected_id==$banners_ids[$i]){echo 'selected';}?> value="<?php echo $banners_ids[$i];?>"><?php echo $title;?></option>
			<?php
				}
			}
		}
		
		public function get_slider_display_options($selected_id=0){
			global $wpw_bsc_functions;
			$options = $wpw_bsc_functions->banner_display_options;			
			foreach($options as $key => $val){
			?>
				<option <?php if($selected_id==$key){echo 'selected';}?> value="<?php echo $key;?>"><?php echo $val;?></option>
			<?php
			}
		}
		
	}
}
global $wpwbs_banner_slider_posttype;
$wpwbs_banner_slider_posttype = new wpwBannerSliderPostType();

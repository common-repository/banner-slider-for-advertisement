<?php
defined( 'ABSPATH' ) or die("No script kiddies please!");

add_action('widgets_init','wpwbs_widget_listings_init');

function wpwbs_widget_listings_init(){
	register_widget('wpwbs_banners_widget');
}

if(!class_exists('wpwbs_banners_widget')){
	class wpwbs_banners_widget extends WP_Widget {
		
		function __construct() {
			$widget_ops = array('classname' => 'widget wpwbs_banners', 'description' => __('It will display banner advertisement sliders.','wpwbs') );
			parent::__construct('wpwbs_banners', __('Banner Slider Advertisement', 'wpwbs'), $widget_ops);			
		}
		
		public function widget($args, $instance) {
		// prints the widget
			extract($args, EXTR_SKIP);
			$banner_id = empty($instance['banner_id']) ? '' : absint($instance['banner_id']);
			
			global $wpw_bsc_functions;
			$args = array(
				'id'		=>	time().'_'.rand(1,1000),
				'action'	=>	'widget_display'
				);
			/**Display Slider**/
			if($banner_id>0){
				echo $before_widget;
				$wpw_bsc_functions->display_slider($banner_id,$args);
				echo $after_widget;
			}
			
		}
		public function update($new_instance, $old_instance) {
		//save the widget
			$instance = array();
			$instance['banner_id'] = ( ! empty( $new_instance['banner_id'] ) ) ? absint( $new_instance['banner_id'] ) : 0;
			return $instance;
		}
		
		public function form($instance) {
		//widgetform in backend
			$instance = wp_parse_args( (array) $instance, array('banner_id' => '') );		
			$banner_id = absint($instance['banner_id']);
		?>
		<p><label for="<?php  echo $this->get_field_id('banner_id'); ?>"> 
		<?php _e('Select Banner','wpwbs');?> :: 
			<select id="<?php  echo $this->get_field_id('banner_id'); ?>" name="<?php echo $this->get_field_name('banner_id'); ?>">
			<option value=""><?php _e('-- Select One --','wpwbs');?></option>
			<?php
			$args = array(
				'posts_per_page'   => -1,
				'post_type'        => WPWBS_BANNER_POST_TYPE,
				'post_status'      => 'publish',
				'orderby'          => 'title',
				'order'            => 'ASC'
			);
			$posts_array = get_posts($args);			
			if($posts_array){
				foreach($posts_array as $postsObj){
					$theid = $postsObj->ID;
					$title = get_the_title($theid);
					if(empty($title))$title = sprintf(__('No Title #%s','wpwbs'),$theid);
					
					?>
					<option value="<?php echo $theid;?>" <?php if($banner_id==$theid)echo 'selected';?>><?php echo $title;?></option>
					<?php
				}
			}		
			?>
			</select>
		</label>
		</p>
		<?php
		}
	}
}
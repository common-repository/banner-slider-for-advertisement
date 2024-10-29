<?php
defined( 'ABSPATH' ) or die("No script kiddies please!");

if(!class_exists('WPWbsFunctions')){
	class WPWbsFunctions {
		public $banner_display_options;
		public $default_display_options;	
		public function __construct() {
			$this->default_display_options = 'after_header';
			$this->banner_display_options = array(
					'after_header'	=>	__('After Header','wpwbs'),
					'before_loop'	=>	__('Before Loop','wpwbs'),
				);
				
			add_action('wp_head', array($this,'enqueue_scripts'),999);
			add_action('wpw_banner_slider_before_header',array($this,'before_header_fun'),1); //before header
			add_action('wpw_banner_slider_after_header',array($this,'after_header_fun'),99); //after header
			add_action('wpw_banner_slider_before_loop',array($this,'before_loop_fun'),1); //before loop
			add_action('wpw_banner_slider_after_loop',array($this,'after_loop_fun'),99); //after loop
			add_action('wpw_banner_slider_before_footer',array($this,'before_footer_fun'),1); //before footer
			add_action('wpw_banner_slider_after_footer',array($this,'after_footer_fun'),99); //after footer
			
		}
		
		/**
		Page Template Javascript & CSS code
		**/
		public function enqueue_scripts(){			
			/**css libs**/
			wp_enqueue_style('wpwbsc-style',WPW_BANNER_SLIDER_URL.'/includes/css/swiper.min.css');
			wp_enqueue_script('wpwbsc-script',WPW_BANNER_SLIDER_URL.'/includes/js/swiper.min.js');
			
			$wpbsc_additional_css = trim(get_option('wpbsc_additional_css'));
			if(!empty($wpbsc_additional_css)){
				echo '<style type="text/css">'.$wpbsc_additional_css.'</style>';
			}
		}
		
		public function get_slider_data($slider_id){
			$sliderData = array();
			$sliderData['id'] = absint($slider_id);
			$sliderData['pagination'] = get_post_meta($slider_id,'disable_pagination',true);
			$sliderData['prev_next'] = get_post_meta($slider_id,'disable_prev_next',true);
			$sliderData['auto_play'] = get_post_meta($slider_id,'disable_auto_play',true);
			$sliderData['loop'] = get_post_meta($slider_id,'disable_loop',true);
			$sliderData['auto_height'] = get_post_meta($slider_id,'disable_auto_height',true);
			$sliderData['keyboard'] = get_post_meta($slider_id,'disable_keyboard',true);
			$sliderData['effect'] = get_post_meta($slider_id,'slider_effect',true);
			$sliderData['autoplay_time'] = get_post_meta($slider_id,'slider_time',true);
			$sliderData['on_mobile'] = get_post_meta($slider_id,'disable_on_mobile',true);			
			$sliderData['content_width'] = get_post_meta($slider_id,'content_width',true);
			if(intval($sliderData['content_width'])>0)$sliderData['content_width'] = $sliderData['content_width'].'px';
				
			$sliderData['random_banners'] = get_post_meta($slider_id,'random_banners',true);
			$sliderData['slide_per_view'] = get_post_meta($slider_id,'slide_per_view',true);
			$sliderData['space_between'] = get_post_meta($slider_id,'space_between',true);			
			
			if($sliderData['autoplay_time']<1000)$sliderData['autoplay_time']=2500;
			if(empty($sliderData['slide_per_view']) || $sliderData['slide_per_view']<1)$sliderData['slide_per_view']=1;
			if(empty($sliderData['space_between']) || $sliderData['space_between']<0)$sliderData['space_between']=30;
			
			$imageArr = array();
			for($b=1;$b<=WPWBS_TOTAL_SLIDER_BANNERS_NUMBER;$b++){
				$image = get_post_meta($slider_id,'image'.$b,true);
				$url = get_post_meta($slider_id,'url'.$b,true);
				if(!empty($image)){
					$imgdata = array();
					$imgdata['img'] = esc_url($image);
					$imgdata['url'] = empty($url)?'#':esc_url($url);
					$imgdata = apply_filters('wpw_banner_slider_single_data_array',$imgdata,$slider_id);					
					$imageArr[] = $imgdata;
				}
			}
			
			$imageArr = apply_filters('wpw_banner_slider_data_array',$imageArr,$slider_id);
			
			if($sliderData['random_banners'])$imageArr = $this->array_random($imageArr);
			
			$sliderData['banners'] = $imageArr;
			$sliderData = apply_filters('wpw_banner_slider_complete_data',$sliderData,$slider_id);			
			return $sliderData;
		}
		
		public function array_random($arr) {
			shuffle($arr);
			$num = count($arr);
			$r = array();
			for($i = 0; $i < $num; $i++){
				$r[] = $arr[$i];
			}
			return $num == 1 ? $r[0] : $r;
		}
		
		public function display_slider($slider_id,$args = array()){
			$slider_data = $this->get_slider_data($slider_id);			
			
			if(get_post_status($slider_id)!='publish')return true;
			if(function_exists('wp_is_mobile') && wp_is_mobile() && $slider_data['on_mobile']==1)return true;
			if(!empty($slider_data['banners']) && count($slider_data['banners'])>0)
				echo '';
			else
				return true;			
			
			$this->display_swipe_slider($slider_data,$args);		
		}
		
		public function display_swipe_slider($slider_data,$args = array()){
			$theid = $args['id'];
			
			do_action('wpw_banner_slider_display_start',$slider_data);
		?>		
			<!-- Swiper -->
			<div class="swiper-container swiper_<?php echo $theid;?> wpbsc_<?php echo $args['action'];?>" style="<?php if($slider_data['content_width']>0)echo 'width:'.$slider_data['content_width'].';max-width:100%;';?>">
				<div class="swiper-wrapper">
					<?php
					$counter = 1;
					$restAllBanners = array();
					foreach($slider_data['banners'] as $key=>$val){
						$url = esc_url(trim($val['url']));
						$img = esc_url($val['img']);
						$onclickUrl='';
						if($url && (strstr($url,'http://') || strstr($url,'https://')))$onclickUrl = 'onclick="window.open(\''.$url.'\')"';
						if($counter==1){
							$single_image_slider = '<div class="swiper-slide" '.$onclickUrl.'><img src="'.$img.'" alt="" /></div>';
							echo apply_filters('wpw_banner_slider_display_single_banner_filter',$single_image_slider,$slider_data);
						}else{
							$restAllBanners[] = '<div class="swiper-slide" '.$onclickUrl.'><img src="'.$img.'" alt="" /></div>';
						}
						$counter++;
					
					}
					
					$restAllBanners = apply_filters('wpw_banner_slider_display_all_banners_filter',$restAllBanners,$slider_data);
					
					?>
				</div>
				<?php if(!empty($slider_data['pagination']))
					echo '';
				else{?>
				<!-- Add Pagination -->
				<div class="swiper-pagination swiper-pagination_<?php echo $theid;?>"></div>
				<?php }?>
				
				<?php if(!empty($slider_data['prev_next']))
					echo '';
				else{?>
				<!-- Add Pagination -->
				<div class="swiper-button-next swiper-next_<?php echo $theid;?>"></div>
				<div class="swiper-button-prev swiper-prev_<?php echo $theid;?>"></div>
				<?php }?>				
			</div>
			<?php do_action('wpw_banner_slider_display_end',$slider_data);?>
			<!-- Initialize Swiper -->
			<?php
			$slide_per_view = empty($slider_data['slide_per_view']) ? 1 : $slider_data['slide_per_view'];
			$space_between = empty($slider_data['space_between']) ? 30 : $slider_data['space_between'];
			if($slide_per_view<=10 && $slide_per_view>=3){
				$slide_per_view_900 = $slide_per_view-1;
				$slide_per_view_700 = $slide_per_view-2;
			}else{
				$slide_per_view_900 = 1;
				$slide_per_view_700 = 1;
			}
			?>
			<script type='text/javascript'>
			jQuery( document ).ready(function() {
				var swiper_<?php echo $theid;?> = new Swiper('.swiper_<?php echo $theid;?>', {
					pagination: '.swiper-pagination_<?php echo $theid;?>',
					paginationClickable: true,
					nextButton: '.swiper-next_<?php echo $theid;?>',
					prevButton: '.swiper-prev_<?php echo $theid;?>',
					<?php
					if(!empty($slider_data['auto_height']))
						echo '';
					else{?>
					autoHeight: true, //enable auto height
					<?php }?>
					<?php
					if(!empty($slider_data['keyboard']))
						echo '';
					else{?>
					keyboardControl: true, //keyboard control
					<?php }?>
					<?php
					if(!empty($slider_data['effect'])){
					?>
					effect: '<?php echo $slider_data['effect'];?>', //fade effect
					<?php }?>
					<?php
					if(!empty($slider_data['loop']))
						echo '';
					else{?>
					loop: true, //infinite loop
					<?php }?>
					<?php
					if(!empty($slider_data['auto_play']))
						echo '';
					else{?>
					//Autoplay
					autoplay: <?php echo $slider_data['autoplay_time'];?>,
					autoplayDisableOnInteraction: false,
					<?php }?>
					slidesPerView: <?php echo $slide_per_view;?>,
					slidesPerColumn: 1,
					spaceBetween: <?php echo $space_between;?>,
					<?php do_action('wpw_banner_slider_display_js_settings',$slider_data);?>
					breakpoints: {
						1024: {
							slidesPerView: <?php echo $slide_per_view;?>,
							spaceBetween: <?php echo $space_between;?>
						},
						900: {
							slidesPerView: <?php echo $slide_per_view_900;?>,
							spaceBetween: <?php echo $space_between;?>
						},
						700: {
							slidesPerView: <?php echo $slide_per_view_700;?>,
							spaceBetween: <?php echo $space_between;?>
						},
						475: {
							slidesPerView: 1,
							spaceBetween: <?php echo $space_between;?>
						}
					}
				});	
				<?php if(!empty($restAllBanners) && count($restAllBanners)>0){
					$restAllBannersStr = implode('%WPBSCSL%',$restAllBanners);
				?>
				var bannersStr = '<?php echo addslashes($restAllBannersStr);?>';
				var bannersRes = bannersStr.split("%WPBSCSL%");
				swiper_<?php echo $theid;?>.appendSlide(bannersRes);
				<?php }?>
			});		
			</script>
			<?php
		}
		
		public function display_slider_conditional($args = array()){
			global $wp_query;
			$wpbsc_id = 0;
			$args['action'] = empty($args['action']) ? '' : $args['action'];
			$wp_query->query['post_type'] = empty($wp_query->query['post_type']) ? '' : $wp_query->query['post_type'];
			if($args['action']=='before_header'){
				if(isset($wp_query->query['paged']) && $wp_query->query['post_type']==APP_POST_TYPE){
					$wpbsc_id = get_option('wpbsc_before_header_archive_id');
				}elseif(is_home() || is_front_page()){
					$wpbsc_id = get_option('wpbsc_before_header_home_id');
				}elseif(WPBSC_TAXONOMY_01!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_01){ //check for category
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_header_id', true);				
				}elseif(WPBSC_TAXONOMY_02!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_02){ //check for tags
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_header_id', true);				
				}elseif(WPBSC_TAXONOMY_03!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_03){ //check for taxonomy01
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_header_id', true);				
				}elseif(WPBSC_TAXONOMY_04!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_04){ //check for taxonomy02
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_header_id', true);				
				}elseif(WPBSC_TAXONOMY_05!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_05){ //check for taxonomy03
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_header_id', true);				
				}elseif(WPBSC_TAXONOMY_06!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_06){ //check for taxonomy04
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_header_id', true);				
				}elseif(WPBSC_TAXONOMY_07!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_07){ //check for taxonomy05
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_header_id', true);				
				}elseif(is_search() || (!empty($wp_query->is_search) && $wp_query->is_search==1)){
					$wpbsc_id = get_option('wpbsc_before_header_search_id');
				}
				
				$wpbsc_id = apply_filters('wpbsc_before_header_slider_id_filter',$wpbsc_id,$args);
			}elseif($args['action']=='after_header'){
				if(isset($wp_query->query['paged']) && $wp_query->query['post_type']==APP_POST_TYPE){
					$wpbsc_id = get_option('wpbsc_after_header_archive_id');
				}elseif(is_home() || is_front_page()){
					$wpbsc_id = get_option('wpbsc_after_header_home_id');
				}elseif(WPBSC_TAXONOMY_01!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_01){ //check for category
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_header_id', true);				
				}elseif(WPBSC_TAXONOMY_02!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_02){ //check for tags
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_header_id', true);				
				}elseif(WPBSC_TAXONOMY_03!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_03){ //check for taxonomy01
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_header_id', true);				
				}elseif(WPBSC_TAXONOMY_04!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_04){ //check for taxonomy02
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_header_id', true);				
				}elseif(WPBSC_TAXONOMY_05!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_05){ //check for taxonomy03
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_header_id', true);				
				}elseif(WPBSC_TAXONOMY_06!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_06){ //check for taxonomy04
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_header_id', true);				
				}elseif(WPBSC_TAXONOMY_07!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_07){ //check for taxonomy05
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_header_id', true);				
				}elseif(is_search() || (!empty($wp_query->is_search) && $wp_query->is_search==1)){
					$wpbsc_id = get_option('wpbsc_after_header_search_id');
				}
				$wpbsc_id = apply_filters('wpbsc_after_header_slider_id_filter',$wpbsc_id,$args);
			}elseif($args['action']=='before_loop'){
				if(is_home() || is_front_page()){
					$wpbsc_id = get_option('wpbsc_before_loop_home_id');
				}elseif(WPBSC_TAXONOMY_01!='' && !empty($wp_query->queried_object->taxonomy)  && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_01){ //check for category
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_loop_id', true);				
				}elseif(WPBSC_TAXONOMY_02!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_02){ //check for tags
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_loop_id', true);				
				}elseif(WPBSC_TAXONOMY_03!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_03){ //check for taxonomy01
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_loop_id', true);				
				}elseif(WPBSC_TAXONOMY_04!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_04){ //check for taxonomy02
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_loop_id', true);				
				}elseif(WPBSC_TAXONOMY_05!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_05){ //check for taxonomy03
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_loop_id', true);				
				}elseif(WPBSC_TAXONOMY_06!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_06){ //check for taxonomy04
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_loop_id', true);				
				}elseif(WPBSC_TAXONOMY_07!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_07){ //check for taxonomy05
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_loop_id', true);				
				}elseif(is_search() || (!empty($wp_query->is_search) && $wp_query->is_search==1)){
					$wpbsc_id = get_option('wpbsc_before_loop_search_id');
				}
				$wpbsc_id = apply_filters('wpbsc_before_loop_slider_id_filter',$wpbsc_id,$args);
			}elseif($args['action']=='after_loop'){
				if(is_home() || is_front_page()){
					$wpbsc_id = get_option('wpbsc_after_loop_home_id');
				}elseif(WPBSC_TAXONOMY_01!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_01){ //check for category
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_loop_id', true);				
				}elseif(WPBSC_TAXONOMY_02!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_02){ //check for tags
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_loop_id', true);				
				}elseif(WPBSC_TAXONOMY_03!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_03){ //check for taxonomy01
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_loop_id', true);				
				}elseif(WPBSC_TAXONOMY_04!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_04){ //check for taxonomy02
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_loop_id', true);				
				}elseif(WPBSC_TAXONOMY_05!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_05){ //check for taxonomy03
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_loop_id', true);				
				}elseif(WPBSC_TAXONOMY_06!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_06){ //check for taxonomy04
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_loop_id', true);				
				}elseif(WPBSC_TAXONOMY_07!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_07){ //check for taxonomy05
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_loop_id', true);				
				}elseif(is_search() || (!empty($wp_query->is_search) && $wp_query->is_search==1)){
					$wpbsc_id = get_option('wpbsc_after_loop_search_id');
				}
				$wpbsc_id = apply_filters('wpbsc_after_loop_slider_id_filter',$wpbsc_id,$args);
			}elseif($args['action']=='before_footer'){
				if(isset($wp_query->query['paged']) && $wp_query->query['post_type']==APP_POST_TYPE){
					$wpbsc_id = get_option('wpbsc_before_footer_archive_id');
				}elseif(is_home() || is_front_page()){
					$wpbsc_id = get_option('wpbsc_before_footer_home_id');
				}elseif(WPBSC_TAXONOMY_01!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_01){ //check for category
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_footer_id', true);				
				}elseif(WPBSC_TAXONOMY_02!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_02){ //check for tags
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_footer_id', true);				
				}elseif(WPBSC_TAXONOMY_03!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_03){ //check for taxonomy01
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_footer_id', true);				
				}elseif(WPBSC_TAXONOMY_04!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_04){ //check for taxonomy02
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_footer_id', true);				
				}elseif(WPBSC_TAXONOMY_05!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_05){ //check for taxonomy03
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_footer_id', true);				
				}elseif(WPBSC_TAXONOMY_06!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_06){ //check for taxonomy04
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_footer_id', true);				
				}elseif(WPBSC_TAXONOMY_07!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_07){ //check for taxonomy05
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_before_footer_id', true);				
				}elseif(is_search() || (!empty($wp_query->is_search) && $wp_query->is_search==1)){
					$wpbsc_id = get_option('wpbsc_before_footer_search_id');
				}
				$wpbsc_id = apply_filters('wpbsc_before_footer_slider_id_filter',$wpbsc_id,$args);
			}elseif($args['action']=='after_footer'){
				if(isset($wp_query->query['paged']) && $wp_query->query['post_type']==APP_POST_TYPE){
					$wpbsc_id = get_option('wpbsc_after_footer_archive_id');
				}elseif(is_home() || is_front_page()){
					$wpbsc_id = get_option('wpbsc_after_footer_home_id');
				}elseif(WPBSC_TAXONOMY_01!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_01){ //check for category
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_footer_id', true);				
				}elseif(WPBSC_TAXONOMY_02!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_02){ //check for tags
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_footer_id', true);				
				}elseif(WPBSC_TAXONOMY_03!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_03){ //check for taxonomy01
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_footer_id', true);				
				}elseif(WPBSC_TAXONOMY_04!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_04){ //check for taxonomy02
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_footer_id', true);				
				}elseif(WPBSC_TAXONOMY_05!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_05){ //check for taxonomy03
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_footer_id', true);				
				}elseif(WPBSC_TAXONOMY_06!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_06){ //check for taxonomy04
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_footer_id', true);				
				}elseif(WPBSC_TAXONOMY_07!='' && !empty($wp_query->queried_object->taxonomy) && $wp_query->queried_object->taxonomy==WPBSC_TAXONOMY_07){ //check for taxonomy05
					$wpbsc_id = get_term_meta($wp_query->queried_object->term_id, 'wpbsc_after_footer_id', true);				
				}elseif(is_search() || (!empty($wp_query->is_search) && $wp_query->is_search==1)){
					$wpbsc_id = get_option('wpbsc_after_footer_search_id');
				}
				$wpbsc_id = apply_filters('wpbsc_after_footer_slider_id_filter',$wpbsc_id,$args);
			}
			
			do_action('wpw_banner_slider_display_conditional_action',$wpbsc_id,$args);
			
			$wpbsc_id = apply_filters('wpbsc_final_display_slider_id_filter',$wpbsc_id,$args);
			
			/**Display Slider**/
			if(!empty($wpbsc_id) && $wpbsc_id>0)$this->display_slider($wpbsc_id,$args);
		}
		
		public function before_header_fun(){
			$args = array(
				'id'		=>	time().'_'.rand(1,1000),
				'action'	=>	'before_header'
				);
			$this->display_slider_conditional($args);
		}
		
		public function after_header_fun(){
			$args = array(
				'id'		=>	time().'_'.rand(1,1000),
				'action'	=>	'after_header'
				);
			$this->display_slider_conditional($args);
		}
		
		public function before_loop_fun(){
			$args = array(
				'id'		=>	time().'_'.rand(1,1000),
				'action'	=>	'before_loop'
				);
			$this->display_slider_conditional($args);
		}
		
		public function after_loop_fun(){
			$args = array(
				'id'		=>	time().'_'.rand(1,1000),
				'action'	=>	'after_loop'
				);
			$this->display_slider_conditional($args);
		}
		
		public function before_footer_fun(){
			$args = array(
				'id'		=>	time().'_'.rand(1,1000),
				'action'	=>	'before_footer'
				);
			$this->display_slider_conditional($args);
		}
		
		public function after_footer_fun(){
			$args = array(
				'id'		=>	time().'_'.rand(1,1000),
				'action'	=>	'after_footer'
				);
			$this->display_slider_conditional($args);
		}
		
	}
}
global $wpw_bsc_functions;
$wpw_bsc_functions = new WPWbsFunctions();
